<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// Fetch user data using PostgreSQL
$user_res = pg_query_params($dbconn, "SELECT * FROM users WHERE id = $1", array($user_id));
$user = pg_fetch_assoc($user_res);

if ($user['status'] == 'banned') { 
    session_destroy(); 
    die("Account Banned."); 
}

// Fetch notice content (If empty, fetch latest)
$notice_res = pg_query($dbconn, "SELECT content FROM notices WHERE id = 1");
$notice = pg_fetch_assoc($notice_res);
if (!$notice || empty($notice['content'])) {
    $notice_res_alt = pg_query($dbconn, "SELECT content FROM notices ORDER BY id DESC LIMIT 1");
    $notice = pg_fetch_assoc($notice_res_alt);
}

// --- Main Panel API Config ---
$api_url = "https://fampage.in/api/v2";
$api_key = "KXKRSPSNsf8cQQKYHDRJjiNq6htJA9Uqm3Dii5GUfS9iIZzRZlhY3AX51dpd";

// Fetch Live Services directly from Main Panel API
$services_by_cat = [];
$ch = curl_init($api_url . "?action=services&key=" . $api_key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$api_services_raw = curl_exec($ch);
curl_close($ch);

$api_services = json_decode($api_services_raw, true);

if (is_array($api_services)) {
    foreach ($api_services as $srv) {
        $cat = trim($srv['category'] ?? 'Other Services');
        
        // Remove YouTube links and Provider Branding
        $raw_desc = $srv['desc'] ?? '';
        $clean_desc = preg_replace('/https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/[^\s]+/i', '', $raw_desc);
        $clean_desc = preg_replace('/fampage\.in|fampage/i', 'Bong Boost', $clean_desc);

        // Calculate 30% Profit
        $original_rate = (float)($srv['rate'] ?? 0);
        $profit_rate = $original_rate * 1.30;

        $services_by_cat[$cat][] = [
            'id' => (string)($srv['service'] ?? ''),
            'name' => (string)($srv['name'] ?? ''),
            'rate' => round($profit_rate, 2),
            'min' => (int)($srv['min'] ?? 0),
            'max' => (int)($srv['max'] ?? 0),
            'desc' => trim((string)$clean_desc)
        ];
    }
    ksort($services_by_cat);
}

$order_success = false; $order_id_created = 0; $order_error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $service_id = (int)$_POST['service_id'];
    $service_name_posted = trim($_POST['service_name'] ?? '');
    $link = filter_var(trim($_POST['link'] ?? ''), FILTER_VALIDATE_URL);
    $quantity = (int)$_POST['quantity'];

    if (!$link) {
        $order_error = "Please enter a valid URL (e.g., https://...)";
    } else {
        $selected_srv = null;
        foreach ($services_by_cat as $cat_list) {
            foreach ($cat_list as $s) {
                if ($s['id'] == $service_id) {
                    $selected_srv = $s;
                    break 2;
                }
            }
        }

        if (!$selected_srv) {
            $order_error = "Invalid Service Selected!";
        } elseif ($quantity < $selected_srv['min'] || $quantity > $selected_srv['max']) {
            $order_error = "Quantity must be between {$selected_srv['min']} and {$selected_srv['max']}";
        } else {
            $total_charge = ($selected_srv['rate'] / 1000) * $quantity;

            if ($user['balance'] < $total_charge) {
                $order_error = "Insufficient wallet balance!";
            } else {
                
                // API Call to Main Panel
                $post_data = [
                    'key' => $api_key,
                    'action' => 'add',
                    'service' => $service_id,
                    'link' => $link,
                    'quantity' => $quantity
                ];

                $ch = curl_init($api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $api_response_raw = curl_exec($ch);
                curl_close($ch);

                $api_response = json_decode($api_response_raw, true);
                $api_order_id = (is_array($api_response) && !empty($api_response['order'])) ? $api_response['order'] : $service_id;

                $srv_name_final = $service_name_posted ?: $selected_srv['name'];

                // PostgreSQL Database Transaction
                pg_query($dbconn, "BEGIN");
                try {
                    $upd_res = pg_query_params($dbconn, "UPDATE users SET balance = balance - $1 WHERE id = $2", array($total_charge, $user_id));
                    if (!$upd_res) { throw new Exception("Failed to update user balance."); }
                    
                    $ins_res = pg_query_params($dbconn, "INSERT INTO orders (user_id, service_id, service_name, link, quantity, charge, remains, api_order_id, status) 
                                  VALUES ($1, $2, $3, $4, $5, $6, $7, $8, 'Pending') RETURNING id", 
                                  array($user_id, $service_id, $srv_name_final, $link, $quantity, $total_charge, $quantity, $api_order_id));
                    
                    if ($ins_res && pg_num_rows($ins_res) > 0) {
                        $created_row = pg_fetch_assoc($ins_res);
                        $order_id_created = $created_row['id'];
                    } else {
                        throw new Exception("Failed to insert order details.");
                    }

                    pg_query($dbconn, "COMMIT");
                    $order_success = true;
                    $user['balance'] -= $total_charge;
                } catch (Exception $e) { 
                    pg_query($dbconn, "ROLLBACK"); 
                    $order_error = "Order Processing Failed: " . $e->getMessage(); 
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bong Boost - Premium SMM Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background: #0f172a;
            background-image: 
                radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.25) 0px, transparent 50%),
                radial-gradient(at 90% 20%, rgba(217, 70, 239, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(14, 165, 233, 0.25) 0px, transparent 50%);
            background-attachment: fixed;
            color: #1e293b; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }
        
        .navbar { 
            background: rgba(15, 23, 42, 0.85) !important; 
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }

        .compact-notice-bar { 
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 16px; 
            padding: 16px 22px; 
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
            margin-bottom: 24px;
        }

        .form-label { font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 6px; }
        .form-control, .form-select { 
            background-color: #f8fafc !important;
            border: 2px solid #cbd5e1 !important;
            color: #0f172a !important;
            font-weight: 600 !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-size: 15px !important;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus { 
            background-color: #ffffff !important;
            border-color: #6366f1 !important; 
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2) !important;
        }

        .btn-3d-primary { 
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            border: none; 
            border-radius: 12px; 
            padding: 14px; 
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-3d-primary:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.5);
            color: #fff;
        }

        .btn-whatsapp { background: #25D366; color: #fff; font-weight: 700; border-radius: 10px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3); }
        .btn-whatsapp:hover { background-color: #1da851; color: #fff; }
        .badge-balance { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); font-size: 14px; padding: 8px 14px; border-radius: 10px; font-weight: 700; }
        
        .desc-box { 
            background: #f1f5f9; 
            border: 1px solid #cbd5e1; 
            border-radius: 12px; 
            padding: 16px; 
            white-space: pre-line; 
            font-size: 14px; 
            color: #334155; 
            max-height: 250px; 
            overflow-y: auto; 
            line-height: 1.6;
            font-weight: 500;
        }

        .info-card { background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 12px; color: #0369a1; font-weight: 600; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold text-white fs-3" href="dashboard.php">Bong Boost</a>
    <div class="d-flex align-items-center flex-wrap gap-2">
      <span class="text-light me-2"><i class="fa-solid fa-user me-1 text-primary"></i> <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
      <span class="badge-balance me-2"><i class="fa-solid fa-wallet me-1"></i> Balance: ₹<?php echo number_format($user['balance'], 2); ?></span>
      <a href="add_fund.php" class="btn btn-sm btn-success"><i class="fa-solid fa-plus me-1"></i> Add Fund</a>
      <a href="free_views.php" class="btn btn-sm btn-warning fw-bold"><i class="fa-solid fa-gift me-1"></i> Free Views</a>
      <a href="orders_history.php" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-clock-rotate-left me-1"></i> Orders History</a>
      <a href="https://wa.me/917718231993?text=Hello%20Bong%20Boost%20Support" target="_blank" class="btn btn-sm btn-whatsapp"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Support</a>
      <a href="logout.php" class="btn btn-sm btn-danger"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="container pb-5">
    
    <!-- Notice Bar -->
    <div class="compact-notice-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <span style="font-size: 26px;">📢</span>
            <div>
                <strong class="fs-6">Important Notice & Rules</strong>
                <div class="small opacity-90">Click 'Read More' to check order and wallet refill rules.</div>
            </div>
        </div>
        <button type="button" class="btn btn-light btn-sm fw-bold px-3 py-2 text-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#noticeModal">
            Read More Notice
        </button>
    </div>

    <!-- Notice Modal -->
    <div class="modal fade" id="noticeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">📢 Bong Boost - Rules & Guidelines</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" style="font-size: 15px; line-height: 1.6; color: #1e293b;">
                    <?php echo !empty($notice['content']) ? nl2br(htmlspecialchars($notice['content'])) : '<i>No notice available right now.</i>'; ?>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create New Order Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-card p-4 p-md-5">
                <h4 class="fw-bold text-center mb-4 text-dark"><i class="fa-solid fa-cart-plus text-primary me-2"></i>Create New Order</h4>
                <?php if($order_error): ?><div class="alert alert-danger rounded-3 fw-bold"><?php echo $order_error; ?></div><?php endif; ?>
                <?php if($order_success): ?><div class="alert alert-success rounded-3 fw-bold">Order placed successfully! Order ID: #<?php echo $order_id_created; ?></div><?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="service_name" id="serviceNameInput" value="">

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="categorySelect" required>
                            <option value="">Select Category</option>
                            <?php foreach ($services_by_cat as $cat => $srvs): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select class="form-select" name="service_id" id="serviceSelect" required>
                            <option value="">Select Service</option>
                        </select>
                    </div>

                    <div id="serviceDetails" class="p-3 mb-3 info-card d-none">
                        <div><strong>Rate per 1000:</strong> ₹<span id="ratePer1k" class="text-success fs-6">0.00</span></div>
                        <div><strong>Min Limit:</strong> <span id="minLimit" class="text-dark">0</span> | <strong>Max Limit:</strong> <span id="maxLimit" class="text-dark">0</span></div>
                    </div>

                    <div id="descContainer" class="mb-3 d-none">
                        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-file-lines me-1"></i> Service Details & Description</label>
                        <div id="serviceDescText" class="desc-box"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link (URL)</label>
                        <input type="url" name="link" class="form-control" placeholder="https://..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantityInput" class="form-control" placeholder="1000" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Total Charge</label>
                        <input type="text" id="totalCharge" class="form-control fw-bold text-success fs-5" value="₹0.00" readonly style="background-color: #f1f5f9 !important;">
                    </div>

                    <button type="submit" name="place_order" class="btn btn-3d-primary w-100 fs-6 py-3"><i class="fa-solid fa-paper-plane me-1"></i> Place Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const servicesData = <?php echo json_encode($services_by_cat, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT); ?> || {};
    
    const catSelect = document.getElementById('categorySelect');
    const srvSelect = document.getElementById('serviceSelect');
    const srvNameHidden = document.getElementById('serviceNameInput');
    const srvDetails = document.getElementById('serviceDetails');
    const descContainer = document.getElementById('descContainer');
    const descText = document.getElementById('serviceDescText');
    const rateSpan = document.getElementById('ratePer1k');
    const minSpan = document.getElementById('minLimit');
    const maxSpan = document.getElementById('maxLimit');
    const qtyInput = document.getElementById('quantityInput');
    const chargeInput = document.getElementById('totalCharge');
    let currentRate = 0;

    catSelect.addEventListener('change', function() {
        srvSelect.innerHTML = '<option value="">Select Service</option>';
        srvDetails.classList.add('d-none');
        descContainer.classList.add('d-none');
        srvNameHidden.value = '';
        
        const selectedCat = this.value;
        if(selectedCat && servicesData[selectedCat]) {
            servicesData[selectedCat].forEach(srv => {
                const opt = document.createElement('option');
                opt.value = srv.id;
                opt.textContent = "[" + srv.id + "] " + srv.name + " - ₹" + parseFloat(srv.rate).toFixed(2);
                opt.dataset.name = srv.name;
                opt.dataset.rate = srv.rate; 
                opt.dataset.min = srv.min; 
                opt.dataset.max = srv.max;
                opt.dataset.desc = srv.desc || '';
                srvSelect.appendChild(opt);
            });
        }
    });

    srvSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if(opt && opt.value) {
            srvNameHidden.value = opt.dataset.name || '';
            currentRate = parseFloat(opt.dataset.rate) || 0;
            rateSpan.textContent = currentRate.toFixed(2);
            minSpan.textContent = opt.dataset.min || 0; 
            maxSpan.textContent = opt.dataset.max || 0;
            srvDetails.classList.remove('d-none'); 
            
            let description = opt.dataset.desc || '';
            if (description && description.trim() !== '') {
                descText.textContent = description.trim();
                descContainer.classList.remove('d-none');
            } else {
                descContainer.classList.add('d-none');
            }
            calculateCharge();
        } else {
            srvNameHidden.value = '';
            srvDetails.classList.add('d-none');
            descContainer.classList.add('d-none');
        }
    });

    qtyInput.addEventListener('input', calculateCharge);

    function calculateCharge() {
        const qty = parseInt(qtyInput.value) || 0;
        if(qty > 0 && currentRate > 0) {
            const total = (currentRate / 1000) * qty;
            
