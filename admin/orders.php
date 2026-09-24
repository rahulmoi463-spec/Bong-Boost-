<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { 
    header("Location: login.php"); 
    exit(); 
}

$msg = "";

// নির্দিষ্ট অর্ডার ডিলিট করার লজিক
if (isset($_GET['delete_order_id'])) {
    $del_id = (int)$_GET['delete_order_id'];
    $del_res = pg_query_params($dbconn, "DELETE FROM orders WHERE id = $1", array($del_id));
    if ($del_res) {
        $msg = "Order #{$del_id} has been deleted successfully!";
    } else {
        $msg = "Failed to delete order.";
    }
}

// অর্ডার স্ট্যাটাস ও প্রোভাইডার অর্ডার আইডি আপডেট করার লজিক
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $custom_order_id = trim($_POST['custom_order_id']);

    $update_res = pg_query_params(
        $dbconn, 
        "UPDATE orders SET status = $1, custom_order_id = $2 WHERE id = $3", 
        array($status, $custom_order_id, $order_id)
    );

    if ($update_res) {
        $msg = "Order #{$order_id} updated successfully!";
    } else {
        $msg = "Failed to update order details.";
    }
}

$orders_res = pg_query($dbconn, "SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .card { background-color: #ffffff; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 border-bottom border-danger">
  <div class="container">
    <a class="navbar-brand text-danger fw-bold fs-4" href="dashboard.php">Admin Panel</a>
    <div>
      <a href="dashboard.php" class="btn btn-sm btn-outline-light me-2">Users & Notice</a>
      <a href="orders.php" class="btn btn-sm btn-light me-2">Orders</a>
      <a href="payments.php" class="btn btn-sm btn-outline-light me-2">Payments</a>
      <a href="../logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container pb-5">
    <?php if($msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-3">
        <h5 class="mb-3">All Customer Orders</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Main Panel Order ID</th>
                        <th>User</th>
                        <th>Service ID</th>
                        <th>Link</th>
                        <th>Qty</th>
                        <th>Charge</th>
                        <th>Status & Provider ID Update</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($orders_res && pg_num_rows($orders_res) > 0): ?>
                        <?php while($o = pg_fetch_assoc($orders_res)): ?>
                        <tr>
                            <td>#<?php echo $o['id']; ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo !empty($o['custom_order_id']) ? htmlspecialchars($o['custom_order_id']) : 'Not Set'; ?>
                                </span>
                            </td>
                            <td><strong><?php echo htmlspecialchars($o['username'] ?? 'User #'.$o['user_id']); ?></strong></td>
                            <td><?php echo htmlspecialchars($o['service_id']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($o['link']); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 150px;"><?php echo htmlspecialchars($o['link']); ?></a></td>
                            <td><?php echo $o['quantity']; ?></td>
                            <td>₹<?php echo number_format((float)$o['charge'], 2); ?></td>
                            <td>
                                <form method="POST" class="d-flex flex-column gap-2">
                                    <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                    <input type="text" name="custom_order_id" class="form-control form-control-sm" placeholder="Main Order ID" value="<?php echo htmlspecialchars($o['custom_order_id'] ?? ''); ?>">
                                    <div class="d-flex align-items-center">
                                        <select name="status" class="form-select form-select-sm me-1" style="width: 110px;">
                                            <option value="Pending" <?php if($o['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Processing" <?php if($o['status']=='Processing') echo 'selected'; ?>>Processing</option>
                                            <option value="Completed" <?php if($o['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                            <option value="Cancelled" <?php if($o['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-primary">Save</button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <a href="orders.php?delete_order_id=<?php echo $o['id']; ?>" onclick="return confirm('Are you sure you want to delete this order? This will not affect user account or balance.');" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash me-1"></i> Delete Order
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="text-center text-muted py-3">No orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
