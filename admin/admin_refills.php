<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit(); 
}

$msg = "";

// Handle Refill Actions (Accept/Complete, Pending or Reject)
if (isset($_GET['action']) && isset($_GET['order_id'])) {
    $o_id = (int)$_GET['order_id'];
    $act = $_GET['action'];

    if ($act == 'approve') {
        // Complete the refill request
        $res = pg_query_params($dbconn, "UPDATE orders SET status = 'Completed' WHERE id = $1", array($o_id));
        if ($res) {
            $msg = "Order #{$o_id} refill request has been APPROVED and marked as Completed!";
        }
    } elseif ($act == 'reject') {
        // Reject refill and revert status back to Completed
        $res = pg_query_params($dbconn, "UPDATE orders SET status = 'Completed' WHERE id = $1", array($o_id));
        if ($res) {
            $msg = "Refill request for Order #{$o_id} has been REJECTED.";
        }
    } elseif ($act == 'processing') {
        // Mark refill as In Progress
        $res = pg_query_params($dbconn, "UPDATE orders SET status = 'Refill Processing' WHERE id = $1", array($o_id));
        if ($res) {
            $msg = "Order #{$o_id} refill is now set to Processing.";
        }
    }
}

// Fetch only orders that have Refill status
$sql = "SELECT o.*, u.username FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        WHERE o.status ILIKE '%Refill%' 
        ORDER BY o.id DESC";

$orders_res = pg_query($dbconn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refill Requests - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .card { background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .nav-link-custom { font-size: 14px; font-weight: 500; }
    </style>
</head>
<body>

<!-- Global Admin Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 border-bottom border-danger">
  <div class="container-fluid px-4">
    <a class="navbar-brand text-danger fw-bold fs-4" href="dashboard.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="adminNavbar">
      <div class="navbar-nav me-auto mb-2 mb-lg-0">
        <a href="dashboard.php" class="btn btn-sm btn-outline-light me-2 mb-1 nav-link-custom">Users & Notice</a>
        <a href="orders.php" class="btn btn-sm btn-outline-light me-2 mb-1 nav-link-custom">Orders</a>
        <a href="admin_refills.php" class="btn btn-sm btn-info text-white fw-bold me-2 mb-1 nav-link-custom"><i class="fa-solid fa-rotate me-1"></i> Refills</a>
        <a href="payments.php" class="btn btn-sm btn-outline-light me-2 mb-1 nav-link-custom">Payments</a>
        <a href="dashboard.php?tab=free_claims" class="btn btn-sm btn-outline-warning fw-bold me-2 mb-1 nav-link-custom"><i class="fa-solid fa-gift me-1"></i> Free Claims</a>
        <a href="sync_services.php" class="btn btn-sm btn-warning text-dark fw-bold me-2 mb-1 nav-link-custom">Sync Services</a>
      </div>
      <div>
        <a href="../logout.php" class="btn btn-sm btn-danger"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
      </div>
    </div>
  </div>
</nav>

<div class="container-fluid px-4 pb-5">
    
    <?php if($msg): ?>
        <div class="alert alert-info alert-dismissible fade show fw-bold" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0 fw-bold text-info"><i class="fa-solid fa-rotate me-2"></i>Manual Refill Management</h5>
            <span class="badge bg-dark fs-6">Pending Requests: <?php echo ($orders_res) ? pg_num_rows($orders_res) : 0; ?></span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Service Name</th>
                        <th>Target Link</th>
                        <th>Quantity</th>
                        <th>Current Status</th>
                        <th>Manual Management Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($orders_res && pg_num_rows($orders_res) > 0): ?>
                        <?php while($o = pg_fetch_assoc($orders_res)): ?>
                        <tr>
                            <td>
                                <strong>#<?php echo $o['id']; ?></strong><br>
                                <small class="text-muted">API ID: <?php echo htmlspecialchars($o['api_order_id']); ?></small>
                            </td>
                            <td><strong><?php echo htmlspecialchars($o['username'] ?? 'User #'.$o['user_id']); ?></strong></td>
                            <td class="text-start" style="max-width: 250px;">
                                <small class="fw-bold"><?php echo htmlspecialchars($o['service_name']); ?></small>
                            </td>
                            <td>
                                <a href="<?php echo htmlspecialchars($o['link']); ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-external-link me-1"></i> Open Link</a>
                            </td>
                            <td><span class="badge bg-secondary"><?php echo $o['quantity']; ?></span></td>
                            <td>
                                <span class="badge bg-info text-dark fs-6"><i class="fa-solid fa-arrows-rotate me-1"></i><?php echo htmlspecialchars($o['status']); ?></span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="admin_refills.php?action=processing&order_id=<?php echo $o['id']; ?>" class="btn btn-warning fw-bold" onclick="return confirm('Set refill status to Processing?')">
                                        <i class="fa-solid fa-spinner me-1"></i> In Progress
                                    </a>
                                    <a href="admin_refills.php?action=approve&order_id=<?php echo $o['id']; ?>" class="btn btn-success fw-bold" onclick="return confirm('Are you sure you completed this refill manually?')">
                                        <i class="fa-solid fa-check me-1"></i> Complete
                                    </a>
                                    <a href="admin_refills.php?action=reject&order_id=<?php echo $o['id']; ?>" class="btn btn-danger fw-bold" onclick="return confirm('Reject this refill request?')">
                                        <i class="fa-solid fa-xmark me-1"></i> Reject
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-muted py-4"><i class="fa-solid fa-inbox me-2"></i>No pending refill requests found at the moment!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
