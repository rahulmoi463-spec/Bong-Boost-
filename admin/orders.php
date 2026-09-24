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

// অর্ডার স্ট্যাটাস, প্রোভাইডার অর্ডার আইডি ও অটো-রিফান্ড আপডেট করার লজিক
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $custom_order_id = trim($_POST['custom_order_id']);

    // ১. আগের অর্ডার ডিটেইলস জেনে রাখা
    $check_ord = pg_query_params($dbconn, "SELECT user_id, charge, status FROM orders WHERE id = $1", array($order_id));
    if ($check_ord && pg_num_rows($check_ord) > 0) {
        $old_order = pg_fetch_assoc($check_ord);
        $user_id = $old_order['user_id'];
        $charge = (float)$old_order['charge'];
        $old_status = strtolower(trim($old_order['status']));

        // ২. যদি নতুন স্ট্যাটাস Cancelled হয় এবং অর্ডারটি আগে Cancelled না থাকে, তবে অটো-রিফান্ড হবে
        if ((strtolower($status) == 'cancelled' || strtolower($status) == 'canceled') && $old_status != 'cancelled' && $old_status != 'canceled') {
            
            // ইউজারের মেইন অ্যাকাউন্টে টাকা যোগ করা
            pg_query_params($dbconn, "UPDATE users SET balance = balance + $1 WHERE id = $2", array($charge, $user_id));

            // ইউজারের ওয়ালেট ট্রানজেকশন হিস্ট্রিতে রেকর্ড যোগ করা
            $desc = "Refund for Cancelled Order #" . $order_id;
            pg_query_params($dbconn, "INSERT INTO transactions (user_id, type, description, amount, created_at) VALUES ($1, 'Credit', $2, $3, NOW())", array($user_id, $desc, $charge));
            
            $msg = "Order #{$order_id} updated & ₹" . number_format($charge, 2) . " refunded to User #{$user_id} successfully!";
        } else {
            $msg = "Order #{$order_id} updated successfully!";
        }

        // ৩. অর্ডারের স্ট্যাটাস ও কাস্টম আইডি আপডেট করা
        pg_query_params(
            $dbconn, 
            "UPDATE orders SET status = $1, custom_order_id = $2 WHERE id = $3", 
            array($status, $custom_order_id, $order_id)
        );
    } else {
        $msg = "Order not found.";
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
        
        /* টেবিল রেসপন্সিভ ও ফিক্সড লেআউট */
        .custom-orders-table {
            min-width: 950px;
        }
        .service-name-col {
            min-width: 220px;
            white-space: normal;
            word-wrap: break-word;
        }
        .nowrap-col {
            white-space: nowrap;
        }
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
        <div class="alert alert-success alert-dismissible fade show fw-bold" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-3 shadow-sm rounded-3">
        <h5 class="mb-3 fw-bold"><i class="fa-solid fa-list-check me-2 text-danger"></i>All Customer Orders</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle custom-orders-table">
                <thead class="table-dark">
                    <tr>
                        <th class="nowrap-col">ID</th>
                        <th class="nowrap-col">Main Panel Order ID</th>
                        <th class="nowrap-col">User</th>
                        <th class="nowrap-col">Service ID</th>
                        <th class="service-name-col">Service Name</th>
                        <th class="nowrap-col">Link</th>
                        <th class="nowrap-col">Qty</th>
                        <th class="nowrap-col">Charge</th>
                        <th class="nowrap-col">Status & Provider ID Update</th>
                        <th class="nowrap-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($orders_res && pg_num_rows($orders_res) > 0): ?>
                        <?php while($o = pg_fetch_assoc($orders_res)): ?>
                        <tr>
                            <td class="nowrap-col">#<?php echo $o['id']; ?></td>
                            <td class="nowrap-col">
                                <span class="badge bg-secondary fs-6">
                                    <?php echo !empty($o['custom_order_id']) ? htmlspecialchars($o['custom_order_id']) : 'Not Set'; ?>
                                </span>
                            </td>
                            <td class="nowrap-col"><strong><?php echo htmlspecialchars($o['username'] ?? 'User #'.$o['user_id']); ?></strong></td>
                            <td class="nowrap-col"><?php echo htmlspecialchars($o['service_id']); ?></td>
                            <td class="service-name-col fw-semibold">
                                <?php echo htmlspecialchars(!empty($o['service_name']) ? $o['service_name'] : ('Service #' . $o['service_id'])); ?>
                            </td>
                            <td class="nowrap-col"><a href="<?php echo htmlspecialchars($o['link']); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 150px;"><?php echo htmlspecialchars($o['link']); ?></a></td>
                            <td class="nowrap-col"><?php echo number_format($o['quantity']); ?></td>
                            <td class="nowrap-col text-success fw-bold">₹<?php echo number_format((float)$o['charge'], 2); ?></td>
                            <td class="nowrap-col">
                                <form method="POST" class="d-flex flex-column gap-2">
                                    <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                    <input type="text" name="custom_order_id" class="form-control form-control-sm" placeholder="Main Order ID" value="<?php echo htmlspecialchars($o['custom_order_id'] ?? ''); ?>">
                                    <div class="d-flex align-items-center">
                                        <select name="status" class="form-select form-select-sm me-1" style="width: 120px;">
                                            <option value="Pending" <?php if($o['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Processing" <?php if($o['status']=='Processing') echo 'selected'; ?>>Processing</option>
                                            <option value="Completed" <?php if($o['status']=='Completed') echo 'selected'; ?>>Completed</option>
                                            <option value="Cancelled" <?php if($o['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-primary">Save</button>
                                    </div>
                                </form>
                            </td>
                            <td class="nowrap-col">
                                <a href="orders.php?delete_order_id=<?php echo $o['id']; ?>" onclick="return confirm('Are you sure you want to delete this order? This will not affect user account or balance.');" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash me-1"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="10" class="text-center text-muted py-3">No orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
