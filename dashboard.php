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

// Fetch Notice Content
$notice_res = pg_query($dbconn, "SELECT content FROM notices WHERE id = 1");
$notice = pg_fetch_assoc($notice_res);
if (!$notice || empty($notice['content'])) {
    $notice_res_alt = pg_query($dbconn, "SELECT content FROM notices ORDER BY id DESC LIMIT 1");
    $notice = pg_fetch_assoc($notice_res_alt);
}

// --- Main Panel API Config ---
$api_url = "https://fampage.in/api/v2";
$api_key = "KXKRSPSNsf8cQQKYHDRJjiNq6htJA9Uqm3Dii5GUfS9iIZzRZlhY3AX51dpd";

$services_by_cat = [];

// Try Fetching Live Services via API with Robust cURL Configuration
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url . "?action=services&key=" . $api_key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
$api_services_raw = curl_exec($ch);
curl_close($ch);

$api_services = json_decode($api_services_raw, true);

if (is_array($api_services) && !empty($api_services) && !isset($api_services['error'])) {
    foreach ($api_services as $srv) {
        $cat = trim($srv['category'] ?? 'Other Services');
        
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
} else {
    // Fallback to Database Services if API fails
    $db_srv_res = pg_query($dbconn, "SELECT * FROM services WHERE status = 'active' ORDER BY category ASC, id ASC");
    if ($db_srv_res && pg_num_rows($db_srv_res) > 0) {
        while ($srv = pg_fetch_assoc($db_srv_res)) {
            $cat = trim($srv['category'] ?? 'Other Services');
            $services_by_cat[$cat][] = [
                'id' => (string)$srv['service_id'],
                'name' => (string)$srv['name'],
                'rate' => (float)$srv['rate'],
                'min' => (int)$srv['min'],
                'max' => (int)$srv['max'],
                'desc' => (string)($srv['description'] ?? '')
            ];
        }
    }
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
                // API Order Placement
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

                pg_query($dbconn, "BEGIN");
                try {
                    $upd_res = pg_query_params($dbconn, "UPDATE users SET balance = balance - $1 WHERE id = $2", array($total_charge, $user_id));
                    if (!$upd_res) { throw new Exception("Failed to update balance."); }
                    
                    $ins_res = pg_query_params($dbconn, "INSERT INTO orders (user_id, service_id, service_name, link, quantity, charge, remains, api_order_id, status) 
                                  VALUES ($1, $2, $3, $4, $5, $6, $7, $8, 'Pending') RETURNING id", 
                                  array($user_id, $service_id, $srv_name_final, $link, $quantity, $total_charge, $quantity, $api_order_id));
                    
                    if ($ins_res && pg_num_rows($ins_res) > 0) {
                        $created_row = pg_fetch_assoc($ins_res);
                        $order_id_created = $created_row['id'];
                    } else {
                        throw new Exception("Failed to save order.");
                    }

                    pg_query($dbconn, "COMMIT");
                    $order_success = true;
                    $user['balance'] -= $total_charge;
                } catch (Exception $e) { 
                    pg_query($dbconn, "ROLLBACK"); 
                    $order_error = "Order Failed: " . $e->getMessage(); 
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
    <title>Bong Boost - SMM Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background: #f1f5f9;
            color: #1e293b; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }
        .navbar { 
            background: #ffffff !important; 
            border-bottom: 1px solid #e2e8f0; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .glass-card { 
            background: #ffffff; 
            border: 1px solid #e2e8f0; 
            border-radius: 24px; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); 
        }
        .promo-banner {
            background: #ffffff;
            border: 2px solid #6366f1;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.1);
        }
        .platform-card {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .platform-card:hover, .platform-card.active {
            border-color: #6366f1;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.18);
            background: #f8fafc;
        }
        .platform-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
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
        }
        .form-control:focus, .form-select:focus { 
            background-color: #ffffff !important; 
            border-color: #6366f1 !important; 
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15) !important; 
        }
        .search-box {
            background-color: #ffffff !important;
            border: 2px solid #6366f1 !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1) !important;
        }
        .btn-3d-primary { 
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); 
            color: #fff; 
            border: none; 
            border-radius: 12px; 
            padding: 14px; 
            font-weight: 700; 
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3); 
            transition: all 0.3s ease;
        }
        .btn-3d-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            color: #fff;
        }
        .badge-balance { 
            background: rgba(34, 197, 94, 0.1); 
            color: #16a34a; 
            border: 1px solid rgba(34, 197, 94, 0.3); 
            font-size: 14px; 
            padding: 8px 14px; 
            border-radius: 10px; 
            font-weight: 700; 
        }
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
            font-weight: 500; 
        }
        .info-card { 
            background: #e0f2fe; 
            border: 1px solid #bae6fd; 
            border-radius: 12px; 
            color: #0369a1; 
            font-weight: 600; 
        }
        .menu-btn {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }
        .menu-btn:hover {
            background: #e2e8f0;
        }
        .offcanvas-custom {
            background: #0f172a;
            color: #fff;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }
        .drawer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.2s ease;
        }
        .drawer-link:hover, .drawer-link.active {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
        }
        .drawer-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        .drawer-link.whatsapp-link {
            background: rgba(37, 211, 102, 0.15);
            color: #25D366;
        }
        .drawer-link.whatsapp-link:hover {
            background: rgba(37, 211, 102, 0.25);
        }
    </style>
</head>
<body>
<!-- Modern Top Navigation Bar -->
<nav class="navbar navbar-expand mb-4 sticky-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-bold text-dark fs-3 d-flex align-items-center gap-2" href="dashboard.php">
        <i class="fa-solid fa-bolt text-primary"></i> Bong Boost
    </a>
    
    <div class="d-flex align-items-center gap-3">
      <!-- Balance Badge -->
      <span class="badge-balance d-none d-sm-inline-block">
        <i class="fa-solid fa-wallet me-1"></i> ₹<?php echo number_format($user['balance'], 2); ?>
      </span>
      
      <!-- 3-Line Menu Trigger Button -->
      <button class="menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="fa-solid fa-bars me-1"></i> <span class="d-none d-md-inline" style="font-size: 14px;">Menu</span>
      </button>
    </div>
  </div>
</nav>

<!-- Professional 3-Line Side Drawer Menu -->
<div class="offcanvas offcanvas-end offcanvas-custom" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
  <div class="offcanvas-header border-bottom border-secondary">
    <div class="d-flex align-items-center gap-2">
      <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
        <i class="fa-solid fa-user"></i>
      </div>
      <div>
        <h6 class="offcanvas-title fw-bold text-white mb-0" id="sidebarMenuLabel"><?php echo htmlspecialchars($user['username']); ?></h6>
        <small class="text-success fw-bold">Balance: ₹<?php echo number_format($user['balance'], 2); ?></small>
      </div>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
    <div>
        <a href="dashboard.php" class="drawer-link active">
            <i class="fa-solid fa-cart-plus"></i> New Order
        </a>
        <a href="add_fund.php" class="drawer-link">
            <i class="fa-solid fa-wallet"></i> Add Fund
        </a>
        <a href="free_views.php" class="drawer-link text-warning">
            <i class="fa-solid fa-gift"></i> Free Views / Gifts
        </a>
        <a href="orders_history.php" class="drawer-link">
            <i class="fa-solid fa-clock-rotate-left"></i> Order History
        </a>
        <a href="https://wa.me/917718231993?text=Hello%20Bong%20Boost%20Support" target="_blank" class="drawer-link whatsapp-link">
            <i class="fa-brands fa-whatsapp"></i> Customer Support
        </a>
        <button type="button" class="drawer-link w-100 text-start border-0 bg-transparent text-info" data-bs-toggle="modal" data-bs-target="#noticeModal" data-bs-dismiss="offcanvas">
            <i class="fa-solid fa-bullhorn"></i> View Rules & Notices
        </button>
    </div>

    <div>
        <hr class="border-secondary mb-3">
        <a href="logout.php" class="drawer-link text-danger">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
  </div>
</div>

<!-- Main Container View -->
<div class="container py-2 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9 col-sm-12">

            <!-- Promo Banner Box (Featured Offer) -->
            <div class="promo-banner position-relative">
                <span class="badge bg-primary text-white fw-bold mb-2"><i class="fa-solid fa-tag me-1"></i> PRICE DROP!</span>
                <h4 class="fw-bold text-dark mb-1">1000 Instagram Likes</h4>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="fs-2 fw-bold text-danger">₹20</span>
                    <span class="fs-5 text-muted text-decoration-line-through">₹25</span>
                </div>
                <p class="text-muted small mb-3"><i class="fa-solid fa-fire text-warning me-1"></i> Limited time Offers 🥰</p>
                <a href="https://t.me/bongboost" target="_blank" class="btn btn-primary rounded-pill fw-bold px-4">
                    <i class="fa-brands fa-telegram me-1"></i> Get Updates on Telegram
                </a>
            </div>

            <div class="glass-card p-4 p-md-5">
                <!-- Select Service Platform Icons Grid Section -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-shapes text-primary me-2"></i>Select Service</h5>
                    <div class="row g-3" id="platformGrid">
                        <div class="col-4 col-sm-4">
                            <div class="platform-card" data-platform="instagram">
                                <i class="fa-brands fa-instagram text-danger fs-1"></i>
                                <span class="platform-name">Instagram</span>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4">
                            <div class="platform-card" data-platform="facebook">
                                <i class="fa-brands fa-facebook text-primary fs-1"></i>
                                <span class="platform-name">Facebook</span>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4">
                            <div class="platform-card" data-platform="telegram">
                                <i class="fa-brands fa-telegram text-info fs-1"></i>
                                <span class="platform-name">Telegram</span>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4">
                            <div class="platform-card" data-platform="whatsapp">
                                <i class="fa-brands fa-whatsapp text-success fs-1"></i>
                                <span class="platform-name">WhatsApp</span>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4">
                            <div class="platform-card" data-platform="youtube">
                                <i class="fa-brands fa-youtube text-danger fs-1"></i>
                                <span class="platform-name">YouTube</span>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4">
                            <div class="platform-card" data-platform="tiktok">
                                <i class="fa-brands fa-tiktok text-dark fs-1"></i>
                                <span class="platform-name">TikTok</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <div class="text-center mb-4">
                    <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-cart-plus text-primary me-2"></i>Place New Order</h4>
                    <p class="text-muted small">Search or select your required category and service below</p>
                </div>

                <?php if($order_error): ?><div class="alert alert-danger rounded-3 fw-bold"><?php echo $order_error; ?></div><?php endif; ?>
                <?php if($order_success): ?><div class="alert alert-success rounded-3 fw-bold">Order placed successfully! Order ID: #<?php echo $order_id_created; ?></div><?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="service_name" id="serviceNameInput" value="">

                    <!-- Smart Search Input Box -->
                    <div class="mb-3">
                        <label class="form-label text-primary"><i class="fa-solid fa-magnifying-glass me-1"></i> Quick Search Services</label>
                        <input type="text" id="serviceSearchInput" class="form-control search-box" placeholder="Type keywords e.g. instagram, youtube, follower, views...">
                    </div>

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

<!-- Auto Pop-up Notice Modal -->
<div class="modal fade" id="noticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.2);">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-bullhorn me-2"></i>Bong Boost - Rules & Guidelines</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 15px; line-height: 1.6; color: #1e293b;">
                <?php echo !empty($notice['content']) ? nl2br(htmlspecialchars($notice['content'])) : '<i>No notice available right now. Welcome to Bong Boost SMM Panel!</i>'; ?>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Please check before order</small>
                <button type="button" class="btn btn-primary rounded-3 px-4 fw-bold" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var noticeModal = new bootstrap.Modal(document.getElementById('noticeModal'));
        noticeModal.show();
    });

    const servicesData = <?php echo json_encode($services_by_cat, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT); ?> || {};
    
    const catSelect = document.getElementById('categorySelect');
    const srvSelect = document.getElementById('serviceSelect');
    const searchInput = document.getElementById('serviceSearchInput');
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

    // Platform Grid Click Event for Instant Filtering
    document.querySelectorAll('.platform-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.platform-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            const platform = this.dataset.platform;
            searchInput.value = platform;
            
            // Auto trigger search filter
            searchInput.dispatchEvent(new Event('input'));
        });
    });

    // Helper to render services list into dropdown
    function populateServices(selectedCat, keyword = '') {
        srvSelect.innerHTML = '<option value="">Select Service</option>';
        srvDetails.classList.add('d-none');
        descContainer.classList.add('d-none');
        srvNameHidden.value = '';
        
        let filterKw = keyword.toLowerCase().trim();

        if (selectedCat && servicesData[selectedCat]) {
            servicesData[selectedCat].forEach(srv => {
                let match = true;
                if (filterKw !== '') {
                    let fullText = (srv.id + " " + srv.name + " " + (srv.desc || '')).toLowerCase();
                    match = fullText.includes(filterKw);
                }
                if (match) {
                    const opt = document.createElement('option');
                    opt.value = srv.id;
                    opt.textContent = "[" + srv.id + "] " + srv.name + " - ₹" + parseFloat(srv.rate).toFixed(2);
                    opt.dataset.name = srv.name;
                    opt.dataset.rate = srv.rate; 
                    opt.dataset.min = srv.min; 
                    opt.dataset.max = srv.max;
                    opt.dataset.desc = srv.desc || '';
                    srvSelect.appendChild(opt);
                }
            });
        } else if (!selectedCat && filterKw !== '') {
            Object.keys(servicesData).forEach(cat => {
                servicesData[cat].forEach(srv => {
                    let fullText = (cat + " " + srv.id + " " + srv.name + " " + (srv.desc || '')).toLowerCase();
                    if (fullText.includes(filterKw)) {
                        const opt = document.createElement('option');
                        opt.value = srv.id;
                        opt.textContent = "[" + srv.id + "] " + srv.name + " - ₹" + parseFloat(srv.rate).toFixed(2);
                        opt.dataset.name = srv.name;
                        opt.dataset.rate = srv.rate; 
                        opt.dataset.min = srv.min; 
                        opt.dataset.max = srv.max;
                        opt.dataset.desc = srv.desc || '';
                        opt.dataset.cat = cat;
                        srvSelect.appendChild(opt);
                    }
                });
            });
        }
    }

    catSelect.addEventListener('change', function() {
        populateServices(this.value, searchInput.value);
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        if (query !== '') {
            let matchedCat = '';
            for (let cat in servicesData) {
                if (cat.toLowerCase().includes(query)) {
                    matchedCat = cat;
                    break;
                }
            }
            if (matchedCat) {
                catSelect.value = matchedCat;
            }
        }
        
        populateServices(catSelect.value, query);
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
            
            if (opt.dataset.cat) {
                catSelect.value = opt.dataset.cat;
            }

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
            chargeInput.value = '₹' + total.toFixed(2);
        } else {
            chargeInput.value = '₹0.00';
        }
    }
</script>
    <?php include 'components/bottom-nav.php'; ?>
</body>
</html>
