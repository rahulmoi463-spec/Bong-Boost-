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
        
        $raw_desc = $srv['desc'] ?? $srv['description'] ?? '';

// ১. যেকোনো ওয়েবসাইট লিঙ্ক ও ইউআরএল (http, https, www) সম্পূর্ণ মুছে ফেলা
$clean_desc = preg_replace('/https?:\/\/[^\s]+/', '', $raw_desc);
$clean_desc = preg_replace('/www\.[^\s]+/', '', $clean_desc);

// ২. যেকোনো ডোমেইন টেক্সট (যেমন: fampage.in, xyz.com) মুছে ফেলা
$clean_desc = preg_replace('/[a-zA-Z0-9-]+\.(com|in|net|org|co|site|xyz|store)[^\s]*/i', '', $clean_desc);

// ৩. প্রোভাইডারের প্যানেলের নাম বদলে Bong Boost করা
$clean_desc = preg_replace('/fampage/i', 'Bong Boost', $clean_desc);

// ৪. যেকোনো টেলিগ্রাম/সোশ্যাল মিডিয়া ইউজারনেম ফিল্টার করা
$clean_desc = preg_replace('/@[a-zA-Z0-9_]+/', '@BongBoostSupport', $clean_desc);
        
        

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
    <title>Bong Boost - Premium SMM Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #070a13;
            --primary-glow: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
            --glass-bg: rgba(15, 23, 42, 0.75);
            --glass-card: rgba(26, 38, 57, 0.85);
            --glass-border: rgba(255, 255, 255, 0.15);
            --neon-cyan: #38bdf8;
            --text-heading: #e2e8f0;
            --text-subtext: #94a3b8;
        }

        body { 
            background: var(--bg-dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.25) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(217, 70, 239, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(14, 165, 233, 0.2) 0px, transparent 50%);
            background-attachment: fixed;
            color: #f1f5f9; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .text-muted, .text-secondary {
            color: #cbd5e1 !important;
        }

        .navbar { 
            background: var(--glass-bg) !important; 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border); 
            padding: 14px 0;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 24px;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6, #d946ef);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .stat-card {
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 18px 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .stat-card .card-title-text {
            color: #cbd5e1 !important;
            font-weight: 700;
        }

        .glass-card { 
            background: var(--glass-card); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); 
            border-radius: 24px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5); 
            padding: 32px;
        }

        .form-label { 
            font-weight: 700; 
            color: #7dd3fc !important; 
            font-size: 13.5px; 
            margin-bottom: 8px; 
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .form-control, .form-select { 
            background-color: rgba(15, 23, 42, 0.9) !important; 
            border: 1.5px solid rgba(56, 189, 248, 0.3) !important; 
            color: #f8fafc !important; 
            font-weight: 600 !important; 
            border-radius: 14px !important; 
            padding: 14px 18px !important; 
            font-size: 15px !important; 
            transition: all 0.25s ease !important;
        }

        .form-control::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .form-control:focus, .form-select:focus { 
            background-color: rgba(15, 23, 42, 0.98) !important; 
            border-color: #38bdf8 !important; 
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.25) !important; 
        }

        .search-box {
            background-color: rgba(15, 23, 42, 0.95) !important;
            border: 1.5px solid rgba(56, 189, 248, 0.5) !important;
            color: #ffffff !important;
        }

        .btn-3d-primary { 
            background: var(--primary-glow); 
            color: #fff; 
            border: none; 
            border-radius: 14px; 
            padding: 16px; 
            font-weight: 800; 
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4); 
            transition: all 0.3s ease;
        }
        .btn-3d-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(139, 92, 246, 0.6);
            color: #fff;
        }

        .badge-balance { 
            background: rgba(34, 197, 94, 0.15); 
            color: #4ade80; 
            border: 1px solid rgba(34, 197, 94, 0.35); 
            font-size: 14px; 
            padding: 8px 16px; 
            border-radius: 12px; 
            font-weight: 700; 
            display: inline-flex;
            align-items: center;
        }

        .desc-box { 
            background: rgba(15, 23, 42, 0.95); 
            border: 1px solid rgba(56, 189, 248, 0.3); 
            border-radius: 14px; 
            padding: 18px; 
            white-space: pre-line; 
            font-size: 14px; 
            color: #e2e8f0; 
            max-height: 250px; 
            overflow-y: auto; 
            font-weight: 500; 
            line-height: 1.6;
        }

        .info-card { 
            background: rgba(14, 165, 233, 0.15); 
            border: 1px solid rgba(14, 165, 233, 0.35); 
            border-radius: 14px; 
            color: #e0f2fe; 
            font-weight: 600; 
        }

        .menu-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .offcanvas-custom {
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(20px);
            color: #fff;
            border-left: 1px solid var(--glass-border);
        }
        .drawer-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 600;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.25s ease;
        }
        .drawer-link:hover, .drawer-link.active {
            background: rgba(99, 102, 241, 0.2);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        .quick-tag {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(56, 189, 248, 0.3);
            color: #e2e8f0;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .quick-tag:hover {
            background: rgba(99, 102, 241, 0.3);
            color: #38bdf8;
            border-color: rgba(56, 189, 248, 0.6);
        }
    </style>
</head>
<body>
<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand navbar-dark mb-4 sticky-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand d-flex align-items-center gap-2" href="dashboard.php">
        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: var(--primary-glow) !important;">
            <i class="fa-solid fa-bolt fs-5"></i>
        </div>
        <span class="brand-logo">Bong Boost</span>
    </a>
    
    <div class="d-flex align-items-center gap-3">
      <!-- Balance Badge -->
      <span class="badge-balance d-none d-sm-inline-flex">
        <i class="fa-solid fa-wallet me-2 text-success"></i> ₹<?php echo number_format($user['balance'], 2); ?>
      </span>
      
      <!-- Menu Button -->
      <button class="menu-btn d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="fa-solid fa-bars-staggered"></i> <span class="d-none d-md-inline" style="font-size: 14px; font-weight: 700;">Menu</span>
      </button>
    </div>
  </div>
</nav>

<!-- Side Drawer Menu -->
<div class="offcanvas offcanvas-end offcanvas-custom" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
  <div class="offcanvas-header border-bottom border-secondary border-opacity-25 p-4">
    <div class="d-flex align-items-center gap-3">
      <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 46px; height: 46px; background: var(--primary-glow); font-size: 18px;">
        <i class="fa-solid fa-user-astronaut"></i>
      </div>
      <div>
        <h6 class="offcanvas-title fw-bold text-white mb-0" id="sidebarMenuLabel"><?php echo htmlspecialchars($user['username']); ?></h6>
        <small class="text-success fw-bold"><i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> Wallet: ₹<?php echo number_format($user['balance'], 2); ?></small>
      </div>
    </div>
    <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between p-3" style="padding-bottom: 75px !important;">
    <div>
        <a href="dashboard.php" class="drawer-link active">
            <i class="fa-solid fa-cart-plus text-info"></i> New Order
        </a>
        <a href="add_fund.php" class="drawer-link">
            <i class="fa-solid fa-wallet text-success"></i> Add Fund
        </a>
        <a href="free_views.php" class="drawer-link">
            <i class="fa-solid fa-gift text-warning"></i> Free Views / Gifts
        </a>
        <a href="orders_history.php" class="drawer-link">
            <i class="fa-solid fa-clock-rotate-left text-primary"></i> Order History
        </a>
        <a href="https://wa.me/917718231993?text=Hello%20Bong%20Boost%20Support" target="_blank" class="drawer-link whatsapp-link" style="color:#25D366;">
            <i class="fa-brands fa-whatsapp fs-5"></i> Customer Support
        </a>
        <button type="button" class="drawer-link w-100 text-start border-0 bg-transparent text-info" data-bs-toggle="modal" data-bs-target="#noticeModal" data-bs-dismiss="offcanvas">
            <i class="fa-solid fa-bullhorn text-danger"></i> View Rules & Notices
        </button>
    </div>

    <div>
        <hr class="border-secondary border-opacity-25 mb-3">
        <a href="logout.php" class="drawer-link text-danger">
            <i class="fa-solid fa-right-from-bracket"></i> Logout Account
        </a>
    </div>
  </div>
</div>

<!-- Main Section -->
<div class="container py-2 pb-5">

    <!-- Top Stats Bar -->
    <div class="row justify-content-center mb-4 g-3 col-lg-7 mx-auto">
        <div class="col-6 col-sm-6">
            <div class="stat-card text-center">
                <div class="card-title-text small mb-1"><i class="fa-solid fa-wallet text-success me-1"></i> Available Balance</div>
                <div class="fs-4 fw-extrabold text-white">₹<?php echo number_format($user['balance'], 2); ?></div>
            </div>
        </div>
        <div class="col-6 col-sm-6">
            <div class="stat-card text-center">
                <div class="card-title-text small mb-1"><i class="fa-solid fa-shield-halved text-info me-1"></i> Account Status</div>
                <div class="fs-4 fw-extrabold text-info">Active VIP</div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9 col-sm-12">
            <div class="glass-card">
                <div class="text-center mb-4">
                    <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-50 px-3 py-2 rounded-pill mb-2 fw-bold" style="font-size: 12px;">
                        <i class="fa-solid fa-rocket me-1"></i> INSTANT HIGH SPEED DELIVERY
                    </span>
                    <h3 class="fw-extrabold text-white mb-1">Create New Order</h3>
                    <p class="text-secondary small">Boost your social media presence in seconds</p>
                </div>

                <?php if($order_error): ?>
                    <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger rounded-4 fw-bold p-3 mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo $order_error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($order_success): ?>
                    <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 text-success rounded-4 fw-bold p-3 mb-4">
                        <i class="fa-solid fa-circle-check me-2"></i>Order placed successfully! Order ID: #<?php echo $order_id_created; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="service_name" id="serviceNameInput" value="">

                    <!-- Smart Search Input Box -->
                    <div class="mb-4">
                        <label class="form-label"><i class="fa-solid fa-magnifying-glass me-1"></i> Quick Search Services</label>
                        <input type="text" id="serviceSearchInput" class="form-control search-box" placeholder="Search Followers, Views, Likes, Telegram...">
                        
                        <!-- Quick Tags -->
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="quick-tag" onclick="quickSearch('Instagram')"><i class="fa-brands fa-instagram text-danger"></i> Instagram</span>
                            <span class="quick-tag" onclick="quickSearch('YouTube')"><i class="fa-brands fa-youtube text-danger"></i> YouTube</span>
                            <span class="quick-tag" onclick="quickSearch('Facebook')"><i class="fa-brands fa-facebook text-primary"></i> Facebook</span>
                            <span class="quick-tag" onclick="quickSearch('Telegram')"><i class="fa-brands fa-telegram text-info"></i> Telegram</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fa-solid fa-layer-group me-1"></i> Category</label>
                        <select class="form-select" id="categorySelect" required>
                            <option value="">Choose a Category...</option>
                            <?php foreach ($services_by_cat as $cat => $srvs): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fa-solid fa-list-check me-1"></i> Service</label>
                        <select class="form-select" name="service_id" id="serviceSelect" required>
                            <option value="">Select Service</option>
                        </select>
                    </div>

                    <div id="serviceDetails" class="p-3 mb-3 info-card d-none">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>Rate per 1000:</span>
                            <span class="fs-5 fw-bold text-success">₹<span id="ratePer1k">0.00</span></span>
                        </div>
                        <div class="small text-secondary d-flex justify-content-between border-top border-info border-opacity-25 pt-2 mt-1">
                            <span>Min Limit: <strong id="minLimit" class="text-white">0</strong></span>
                            <span>Max Limit: <strong id="maxLimit" class="text-white">0</strong></span>
                        </div>
                    </div>

                    <div id="descContainer" class="mb-3 d-none">
                        <label class="form-label"><i class="fa-solid fa-circle-info me-1"></i> Service Details & Description</label>
                        <div id="serviceDescText" class="desc-box"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fa-solid fa-link me-1"></i> Target Link (URL)</label>
                        <input type="url" name="link" class="form-control" placeholder="https://instagram.com/username..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fa-solid fa-arrow-up-9-1 me-1"></i> Quantity</label>
                        <input type="number" name="quantity" id="quantityInput" class="form-control" placeholder="e.g. 1000" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="fa-solid fa-receipt me-1"></i> Total Charge</label>
                        <input type="text" id="totalCharge" class="form-control fw-bold text-success fs-4" value="₹0.00" readonly style="background-color: rgba(15, 23, 42, 0.95) !important; border-color: rgba(34, 197, 94, 0.4) !important;">
                    </div>

                    <button type="submit" name="place_order" class="btn btn-3d-primary w-100">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Order Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Notice Modal -->
<div class="modal fade" id="noticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content text-white" style="background: rgba(15, 23, 42, 0.98); backdrop-filter: blur(25px); border-radius: 24px; border: 1px solid var(--glass-border); box-shadow: 0 25px 50px rgba(0,0,0,0.8);">
            <div class="modal-header border-bottom border-secondary border-opacity-25 p-4" style="background: rgba(30, 41, 59, 0.5);">
                <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-bullhorn text-warning me-2"></i>Rules & Guidelines</h5>
                <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 15px; line-height: 1.7; color: #e2e8f0; max-height: 55vh; overflow-y: auto;">
                
                <?php echo !empty($notice['content']) ? nl2br(htmlspecialchars($notice['content'])) : '<i>No notice available right now. Welcome to Bong Boost SMM Panel!</i>'; ?>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 p-3 d-flex justify-content-between">
                <small class="text-secondary"><i class="fa-solid fa-circle-info me-1"></i> Please check before order</small>
                <button type="button" class="btn btn-primary rounded-3 px-4 fw-bold" data-bs-dismiss="modal" style="background: var(--primary-glow); border: none;">I Understand</button>
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

    function quickSearch(tag) {
        searchInput.value = tag;
        searchInput.dispatchEvent(new Event('input'));
    }

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
                descText.innerHTML = description.trim().replace(/\n/g, '<br>');
                
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

