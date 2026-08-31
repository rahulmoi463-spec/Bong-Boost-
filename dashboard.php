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

// Fetch user profile
$user_res = pg_query_params($dbconn, "SELECT * FROM users WHERE id = $1", array($user_id));
$user = pg_fetch_assoc($user_res);

if ($user['status'] == 'banned') { 
    session_destroy(); 
    die("Account Banned."); 
}

// Fetch Main API Services
$api_url = "https://fampage.in/api/v2";
$api_key = "KXKRSPSNsf8cQQKYHDRJjiNq6htJA9Uqm3Dii5GUfS9iIZzRZlhY3AX51dpd";

$services_by_cat = [];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url . "?action=services&key=" . $api_key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$api_services_raw = curl_exec($ch);
curl_close($ch);

$api_services = json_decode($api_services_raw, true);

if (is_array($api_services) && !empty($api_services) && !isset($api_services['error'])) {
    foreach ($api_services as $srv) {
        $cat = trim($srv['category'] ?? 'Other Services');
        
        $raw_desc = $srv['desc'] ?? '';
        $clean_desc = preg_replace('/https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/[^\s]+/i', '', $raw_desc);
        $clean_desc = preg_replace('/fampage\.in|fampage/i', 'Bong Boost', $clean_desc);

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
}

// Order Handling
$order_success = false; $order_id_created = 0; $order_error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $service_id = (int)$_POST['service_id'];
    $service_name_posted = trim($_POST['service_name'] ?? '');
    $link = filter_var(trim($_POST['link'] ?? ''), FILTER_VALIDATE_URL);
    $quantity = (int)$_POST['quantity'];

    if (!$link) {
        $order_error = "Please enter a valid link!";
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
                $post_data = [
                    'key' => $api_key, 'action' => 'add', 'service' => $service_id,
                    'link' => $link, 'quantity' => $quantity
                ];

                $ch = curl_init($api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $api_response_raw = curl_exec($ch);
                curl_close($ch);

                $api_response = json_decode($api_response_raw, true);
                $api_order_id = $api_response['order'] ?? $service_id;

                pg_query($dbconn, "BEGIN");
                try {
                    pg_query_params($dbconn, "UPDATE users SET balance = balance - $1 WHERE id = $2", array($total_charge, $user_id));
                    $ins_res = pg_query_params($dbconn, "INSERT INTO orders (user_id, service_id, service_name, link, quantity, charge, remains, api_order_id, status) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, 'Pending') RETURNING id", array($user_id, $service_id, $service_name_posted ?: $selected_srv['name'], $link, $quantity, $total_charge, $quantity, $api_order_id));
                    
                    if ($ins_res && pg_num_rows($ins_res) > 0) {
                        $order_id_created = pg_fetch_assoc($ins_res)['id'];
                    }
                    pg_query($dbconn, "COMMIT");
                    $order_success = true;
                    $user['balance'] -= $total_charge;
                } catch (Exception $e) { 
                    pg_query($dbconn, "ROLLBACK"); 
                    $order_error = "Order Failed!"; 
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bong Boost - Premium Boost Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f6f9; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        /* Interactive Platform Cards */
        .platform-box {
            background: #ffffff; border: 2px solid #e2e8f0; border-radius: 18px; padding: 16px 10px;
            text-align: center; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .platform-box:hover, .platform-box.active {
            transform: translateY(-4px); border-color: #4f46e5 !important;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.15); background: #f8fafc;
        }
        .platform-icon { font-size: 32px; margin-bottom: 6px; display: block; }
        .platform-title { font-weight: 800; font-size: 14px; display: block; color: #0f172a; }

        /* Sub Category Tabs */
        .sub-cat-badge {
            background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 30px; padding: 8px 18px;
            font-weight: 700; font-size: 13px; color: #334155; cursor: pointer; transition: all 0.2s; display: inline-block;
        }
        .sub-cat-badge:hover, .sub-cat-badge.active {
            background: #4f46e5; color: #ffffff; border-color: #4f46e5; shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .form-control, .form-select { 
            background-color: #f8fafc !important; border: 2px solid #cbd5e1 !important; 
            border-radius: 14px !important; padding: 14px !important; font-weight: 600 !important; 
        }
        .btn-order { 
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; 
            border-radius: 14px; padding: 16px; font-weight: 800; font-size: 16px;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35); transition: all 0.3s;
        }
        .btn-order:hover { transform: translateY(-2px); opacity: 0.95; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand mb-4 bg-white border-bottom py-3">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-extrabold text-dark fs-3 d-flex align-items-center gap-2" href="dashboard.php">
        <i class="fa-solid fa-bolt text-primary fs-2"></i> Bong Boost
    </a>
    <div class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-3 fs-6 font-bold">
        <i class="fa-solid fa-wallet me-1"></i> ₹<?php echo number_format($user['balance'], 2); ?>
    </div>
  </div>
</nav>

<div class="container py-2 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9 col-sm-12">
            <div class="glass-card p-4 p-md-5">
                
                <!-- 1. Platform Selection Grid -->
                <div class="mb-4">
                    <h6 class="fw-extrabold text-uppercase text-secondary mb-3 fs-7" style="letter-spacing: 0.5px;">
                        <i class="fa-solid fa-fire text-warning me-1"></i> ১. প্ল্যাটফর্ম সিলেক্ট করুন
                    </h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="platform-box active" onclick="selectPlatform('instagram', this)">
                                <i class="fa-brands fa-instagram platform-icon text-danger"></i>
                                <span class="platform-title">Instagram</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="platform-box" onclick="selectPlatform('facebook', this)">
                                <i class="fa-brands fa-facebook platform-icon text-primary"></i>
                                <span class="platform-title">Facebook</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="platform-box" onclick="selectPlatform('youtube', this)">
                                <i class="fa-brands fa-youtube platform-icon text-danger"></i>
                                <span class="platform-title">YouTube</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="platform-box" onclick="selectPlatform('whatsapp', this)">
                                <i class="fa-brands fa-whatsapp platform-icon text-success"></i>
                                <span class="platform-title">WhatsApp</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="platform-box" onclick="selectPlatform('telegram', this)">
                                <i class="fa-brands fa-telegram platform-icon text-info"></i>
                                <span class="platform-title">Telegram</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="platform-box" onclick="selectPlatform('tiktok', this)">
                                <i class="fa-brands fa-tiktok platform-icon text-dark"></i>
                                <span class="platform-title">TikTok</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Sub-Category Tabs Container -->
                <div class="mb-4">
                    <h6 class="fw-extrabold text-uppercase text-secondary mb-3 fs-7" style="letter-spacing: 0.5px;">
                        <i class="fa-solid fa-list-check text-primary me-1"></i> ২. সার্ভিস ক্যাটাগরি বেছে নিন
                    </h6>
                    <div id="subCategoryContainer" class="d-flex flex-wrap gap-2">
                        <!-- Dynamic Sub-category Badges Render Here -->
                    </div>
                </div>

                <hr class="my-4">

                <?php if($order_error): ?><div class="alert alert-danger rounded-3 fw-bold mb-3"><?php echo $order_error; ?></div><?php endif; ?>
                <?php if($order_success): ?><div class="alert alert-success rounded-3 fw-bold mb-3">Order Placed Successfully! Order ID: #<?php echo $order_id_created; ?></div><?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="service_name" id="serviceNameInput">

                    <!-- Filtered Service Options -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">সার্ভিস প্যাক (Service Variant)</label>
                        <select class="form-select" name="service_id" id="serviceSelect" required>
                            <option value="">সার্ভিস লোড হচ্ছে...</option>
                        </select>
                    </div>

                    <!-- Details Box -->
                    <div id="serviceDetails" class="p-3 mb-3 bg-light rounded-3 border d-none">
                        <div class="d-flex justify-content-between">
                            <span>মূল্য (প্রতি ১০০০): <strong class="text-success fs-6" id="ratePer1k">₹0.00</strong></span>
                            <span>সীমা: <strong id="minLimit">0</strong> - <strong id="maxLimit">0</strong></span>
                        </div>
                    </div>

                    <div id="descContainer" class="mb-3 d-none">
                        <div id="serviceDescText" class="p-3 bg-white border rounded-3 text-secondary fs-7"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">আপনার প্রোফাইল / পোস্ট লিংক</label>
                        <input type="url" name="link" class="form-control" placeholder="https://..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">পরিমাণ (Quantity)</label>
                        <input type="number" name="quantity" id="quantityInput" class="form-control" placeholder="1000" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">মোট চার্জ (Total Cost)</label>
                        <input type="text" id="totalCharge" class="form-control fw-extrabold text-success fs-5" value="₹0.00" readonly style="background-color: #f1f5f9 !important;">
                    </div>

                    <button type="submit" name="place_order" class="btn btn-order w-100">
                        <i class="fa-solid fa-rocket me-2"></i> এখনই অর্ডার করুন
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const servicesData = <?php echo json_encode($services_by_cat, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT); ?> || {};

    let currentPlatform = 'instagram';
    let currentSubCat = '';

    const subCatContainer = document.getElementById('subCategoryContainer');
    const srvSelect = document.getElementById('serviceSelect');
    const srvNameInput = document.getElementById('serviceNameInput');
    const srvDetails = document.getElementById('serviceDetails');
    const rateSpan = document.getElementById('ratePer1k');
    const minSpan = document.getElementById('minLimit');
    const maxSpan = document.getElementById('maxLimit');
    const descContainer = document.getElementById('descContainer');
    const descText = document.getElementById('serviceDescText');
    const qtyInput = document.getElementById('quantityInput');
    const chargeInput = document.getElementById('totalCharge');
    let currentRate = 0;

    function selectPlatform(platform, element) {
        currentPlatform = platform;
        document.querySelectorAll('.platform-box').forEach(b => b.classList.remove('active'));
        if(element) element.classList.add('active');

        // Render Sub Categories
        renderSubCategories();
    }

    function renderSubCategories() {
        subCatContainer.innerHTML = '';
        const matchedCategories = Object.keys(servicesData).filter(cat => 
            cat.toLowerCase().includes(currentPlatform.toLowerCase())
        );

        if (matchedCategories.length === 0) {
            subCatContainer.innerHTML = '<span class="text-muted fs-7">কোনো সার্ভিস উপলব্ধ নেই</span>';
            srvSelect.innerHTML = '<option value="">নেই</option>';
            return;
        }

        matchedCategories.forEach((cat, index) => {
            const badge = document.createElement('div');
            badge.className = `sub-cat-badge ${index === 0 ? 'active' : ''}`;
            
            // Clean title display (e.g., Extract "Followers", "Likes" etc.)
            let shortName = cat.replace(new RegExp(currentPlatform, 'gi'), '').replace(/[:\-]/g, '').trim();
            badge.innerHTML = shortName ? `<i class="fa-solid fa-star me-1 text-warning"></i> ${shortName}` : cat;
            
            badge.onclick = function() {
                document.querySelectorAll('.sub-cat-badge').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                loadServicesForCategory(cat);
            };

            subCatContainer.appendChild(badge);
        });

        // Load services for first sub category automatically
        if (matchedCategories.length > 0) {
            loadServicesForCategory(matchedCategories[0]);
        }
    }

    function loadServicesForCategory(categoryKey) {
        srvSelect.innerHTML = '';
        srvDetails.classList.add('d-none');
        descContainer.classList.add('d-none');

        const items = servicesData[categoryKey] || [];
        items.forEach(srv => {
            const opt = document.createElement('option');
            opt.value = srv.id;
            opt.textContent = `${srv.name} - ₹${parseFloat(srv.rate).toFixed(2)}`;
            opt.dataset.name = srv.name;
            opt.dataset.rate = srv.rate;
            opt.dataset.min = srv.min;
            opt.dataset.max = srv.max;
            opt.dataset.desc = srv.desc || '';
            srvSelect.appendChild(opt);
        });

        if (items.length > 0) {
            srvSelect.selectedIndex = 0;
            updateServiceDetails();
        }
    }

    function updateServiceDetails() {
        const opt = srvSelect.options[srvSelect.selectedIndex];
        if (opt && opt.value) {
            srvNameInput.value = opt.dataset.name;
            currentRate = parseFloat(opt.dataset.rate) || 0;
            rateSpan.textContent = '₹' + currentRate.toFixed(2);
            minSpan.textContent = opt.dataset.min;
            maxSpan.textContent = opt.dataset.max;
            srvDetails.classList.remove('d-none');

            if (opt.dataset.desc && opt.dataset.desc.trim() !== '') {
                descText.textContent = opt.dataset.desc;
                descContainer.classList.remove('d-none');
            } else {
                descContainer.classList.add('d-none');
            }
            calculateCharge();
        }
    }

    srvSelect.addEventListener('change', updateServiceDetails);
    qtyInput.addEventListener('input', calculateCharge);

    function calculateCharge() {
        const qty = parseInt(qtyInput.value) || 0;
        if (qty > 0 && currentRate > 0) {
            chargeInput.value = '₹' + ((currentRate / 1000) * qty).toFixed(2);
        } else {
            chargeInput.value = '₹0.00';
        }
    }

    // Default Load Instagram
    renderSubCategories();
</script>
    <?php include 'components/bottom-nav.php'; ?>
</body>
</html>
