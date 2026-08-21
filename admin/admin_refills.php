<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fix for db.php path inside admin folder
if (file_exists('../config/db.php')) {
    require_once '../config/db.php';
} elseif (file_exists('config/db.php')) {
    require_once 'config/db.php';
}

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Complete / Reject Request Action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $req_id = (int)$_GET['id'];
    $status = ($_GET['action'] == 'completed') ? 'Completed' : 'Rejected';
    
    if (isset($dbconn)) {
        pg_query_params($dbconn, "UPDATE refill_requests SET status = $1 WHERE id = $2", array($status, $req_id));
    }
    header("Location: admin_refills.php");
    exit();
}

$requests_res = null;
if (isset($dbconn)) {
    $query = "SELECT r.*, u.username, o.link, s.name as service_name 
              FROM refill_requests r 
              LEFT JOIN users u ON r.user_id = u.id 
              LEFT JOIN orders o ON r.order_id = o.id 
              LEFT JOIN services s ON o.service_id = s.id 
              ORDER BY r.id DESC";
    $requests_res = pg_query($dbconn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Refill Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: sans-serif; }
    </style>
</head>
<body class="p-3 p-md-4">
    <div class="container-fluid">
        <!-- Admin Navigation Bar -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <a href="dashboard.php" class="btn btn-outline-primary">Dashboard</a>
            <a href="orders.php" class="btn btn-outline-primary">Orders</a>
            <a href="payments.php" class="btn btn-outline-primary">Payments</a>
            <a href="admin_refills.php" class="btn btn-warning fw-bold">Refill Requests</a>
        </div>

        <h3 class="mb-4 fw-bold">Customer Refill Requests</h3>
        
        <div class="card p-3 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Req ID</th>
                            <th>User</th>
                            <th>Our Order ID</th>
                            <th class="table-primary text-dark">Main Panel Order ID</th>
                            <th>Service</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($requests_res && pg_num_rows($requests_res) > 0): ?>
                            <?php while($row = pg_fetch_assoc($requests_res)): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username'] ?? 'N/A'); ?></td>
                                <td>#<?php echo $row['order_id']; ?></td>
                                <td class="table-primary fw-bold text-danger fs-5">
                                    <?php echo htmlspecialchars($row['api_order_id'] ?? 'N/A'); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['service_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php if(!empty($row['link'])): ?>
                                        <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank" class="btn btn-sm btn-outline-info text-dark">Open Link</a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Completed' ? 'success' : 'danger'); ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['status'] == 'Pending'): ?>
                                        <a href="admin_refills.php?action=completed&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success mb-1">Mark Completed</a>
                                        <a href="admin_refills.php?action=rejected&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger mb-1">Reject</a>
                                    <?php else: ?>
                                        <span class="text-muted fw-semibold">Done</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">No refill requests found.</td></tr>
                            <?php if(!$requests_res): ?>
                                <tr><td colspan="8" class="text-center text-danger">Database connection error or table 'refill_requests' missing! Check SQL setup.</td></tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
