<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Status Change / Partial Refund Processing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_order'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $remains = (int)$_POST['remains'];

    // Fetch Order details
    $o_res = pg_query_params($dbconn, "SELECT * FROM orders WHERE id = $1", array($order_id));
    $o = pg_fetch_assoc($o_res);
    
    if ($o) {
        if ($status === 'Canceled' || $status === 'Partial') {
            $total_qty = (float)$o['quantity'];
            $total_charge = (float)$o['charge'];
            $refund_amount = ($total_qty > 0) ? ($remains / $total_qty) * $total_charge : 0;

            pg_query($dbconn, "BEGIN");
            try {
                $upd_user = pg_query_params($dbconn, "UPDATE users SET balance = balance + $1 WHERE id = $2", array($refund_amount, $o['user_id']));
                if (!$upd_user) { throw new Exception("User balance update failed."); }

                $upd_order = pg_query_params($dbconn, "UPDATE orders SET status = $1, remains = $2 WHERE id = $3", array($status, $remains, $order_id));
                if (!$upd_order) { throw new Exception("Order update failed."); }

                pg_query($dbconn, "COMMIT");
            } catch (Exception $e) {
                pg_query($dbconn, "ROLLBACK");
            }
        } else {
            pg_query_params($dbconn, "UPDATE orders SET status = $1, remains = $2 WHERE id = $3", array($status, $remains, $order_id));
        }
    }
}

// IP Helper Function (Fetch City & ISP Automatically)
function getIpLocationDetails($ip) {
    if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1') {
        return "Localhost";
    }
    
    // Free IP Lookup API
    $url = "http://ip-api.com/json/" . trim($ip) . "?fields=status,city,isp";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    if ($data && isset($data['status']) && $data['status'] === 'success') {
        $city = !empty($data['city']) ? $data['city'] : 'Unknown City';
        $isp = !empty($data['isp']) ? $data['isp'] : 'Unknown ISP';
        return htmlspecialchars($ip) . " <br><small class='text-primary fw-bold'>(" . htmlspecialchars($city) . " - " . htmlspecialchars($isp) . ")</small>";
    }
    
    return htmlspecialchars($ip);
}

$search_query = "";

// Handle Order Search by Username using PostgreSQL ILIKE
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = trim($_GET['search']);
    $search_param = '%' . $search_query . '%';
    
    $query = "SELECT orders.*, users.username, users.last_ip 
              FROM orders 
              JOIN users ON orders.user_id = users.id 
              WHERE users.username ILIKE $1 
              ORDER BY orders.id DESC";
    $orders_res = pg_query_params($dbconn, $query, array($search_param));
} else {
    $query = "SELECT orders.*, users.username, users.last_ip 
              FROM orders 
              JOIN users ON orders.user_id = users.id 
              ORDER BY orders.id DESC";
    $orders_res = pg_query($dbconn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style> 
    body { background-color: #f8f9fa; color: #212529; } 
    .card { background-color: #ffffff; border: 1px solid #dee2e6; color: #212529; box-shadow: 0 2px 4px rgba(0,0,0,0.05); } 
    .table { color: #212529; }
    .table code { color: #d63384; font-weight: 600; }
    .table-dark { --bs-table-bg: #ffffff; --bs-table-color: #212529; border-color: #dee2e6; }
    .table-striped>tbody>tr:nth-of-type(odd)>* { --bs-table-bg-type: #f8f9fa; color: #212529; }
    .form-control { background-color: #ffffff !important; color: #212529 !important; border: 1px solid #ced4da !important; }
    </style>
</head>
<body>
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Orders</h3>
        <a href="dashboard.php" class="btn btn-outline-dark btn-sm">Back to Admin</a>
    </div>
    
    <div class="card p-3">
        <!-- Search Bar Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="m-0 text-dark">Order History</h5>
            <form method="GET" action="orders.php" class="d-flex" style="max-width: 320px; width: 100%;">
                <input type="text" name="search" class="form-control form-control-sm border-secondary me-2" placeholder="Search Username..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="btn btn-sm btn-primary me-1"><i class="fa-solid fa-magnifying-glass"></i></button>
                <?php if(!empty($search_query)): ?>
                    <a href="orders.php" class="btn btn-sm btn-secondary" title="Reset Search"><i class="fa-solid fa-xmark"></i></a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>IP Details (Location & ISP)</th>
                        <th>Main Service ID</th>
                        <th>Service Name</th>
                        <th>Link</th>
                        <th>Qty</th>
                        <th>Charge</th>
                        <th>Status / Remains</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders_res && pg_num_rows($orders_res) > 0): ?>
                        <?php while($o = pg_fetch_assoc($orders_res)): 
                            $raw_link = trim($o['link']);
                            if (!preg_match("~^(?:f|ht)tps?://~i", $raw_link)) {
                                $target_url = "https://" . $raw_link;
                            } else {
                                $target_url = $raw_link;
                            }
                            $admin_serv_id = $o['service_id'];
                            $user_ip = !empty($o['last_ip']) ? $o['last_ip'] : '';
                        ?>
                        <tr>
                            <td>#<?php echo $o['id']; ?></td>
                            <td><?php echo htmlspecialchars($o['username']); ?></td>
                            <td><?php echo getIpLocationDetails($user_ip); ?></td>
                            <td>
                                <span class="badge bg-warning text-dark fs-6">
                                    <?php echo htmlspecialchars($admin_serv_id); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(!empty($o['service_name']) ? $o['service_name'] : 'N/A'); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($target_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info text-dark fw-bold">
                                    Open Link
                                </a>
                            </td>
                            <td><?php echo number_format($o['quantity']); ?></td>
                            <td>₹<?php echo number_format($o['charge'], 2); ?></td>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                <td>
                                    <select name="status" class="form-select form-select-sm mb-1">
                                        <option value="Pending" <?php if($o['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="In progress" <?php if($o['status']=='In progress') echo 'selected'; ?>>In progress</option>
                                        <option value="Completed" <?php if($o['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                        <option value="Partial" <?php if($o['status']=='Partial') echo 'selected'; ?>>Partial</option>
                                        <option value="Canceled" <?php if($o['status']=='Canceled') echo 'selected'; ?>>Canceled</option>
                                    </select>
                                    <input type="number" name="remains" value="<?php echo $o['remains']; ?>" class="form-control form-control-sm" placeholder="Remains">
                                </td>
                                <td><button type="submit" name="update_order" class="btn btn-sm btn-primary">Update</button></td>
                            </form>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No orders found matching "<?php echo htmlspecialchars($search_query); ?>"</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
