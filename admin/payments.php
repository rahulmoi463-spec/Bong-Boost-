<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Approve Payment
if (isset($_GET['approve'])) {
    $p_id = (int)$_GET['approve'];
    
    // Fetch pending payment details
    $p_res = pg_query_params($dbconn, "SELECT * FROM payments WHERE id = $1 AND status = 'Pending'", array($p_id));
    $p = pg_fetch_assoc($p_res);

    if ($p) {
        pg_query($dbconn, "BEGIN");
        try {
            $upd_pay = pg_query_params($dbconn, "UPDATE payments SET status = 'Approved' WHERE id = $1", array($p_id));
            if (!$upd_pay) { throw new Exception("Failed to update payment status."); }

            $upd_user = pg_query_params($dbconn, "UPDATE users SET balance = balance + $1 WHERE id = $2", array($p['amount'], $p['user_id']));
            if (!$upd_user) { throw new Exception("Failed to update user balance."); }

            pg_query($dbconn, "COMMIT");
        } catch (Exception $e) {
            pg_query($dbconn, "ROLLBACK");
        }
    }
    header("Location: payments.php");
    exit();
}

// Reject Payment
if (isset($_GET['reject'])) {
    $p_id = (int)$_GET['reject'];
    pg_query_params($dbconn, "UPDATE payments SET status = 'Rejected' WHERE id = $1", array($p_id));
    header("Location: payments.php");
    exit();
}

$search_query = "";

// Handle Search by Username or Txn ID using PostgreSQL ILIKE (Case-insensitive search)
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search_query = trim($_GET['search']);
    $search_param = '%' . $search_query . '%';
    
    $query = "SELECT payments.*, users.username 
              FROM payments 
              JOIN users ON payments.user_id = users.id 
              WHERE users.username ILIKE $1 OR payments.txn_id ILIKE $1 
              ORDER BY payments.id DESC";
    $payments_res = pg_query_params($dbconn, $query, array($search_param));
} else {
    $query = "SELECT payments.*, users.username 
              FROM payments 
              JOIN users ON payments.user_id = users.id 
              ORDER BY payments.id DESC";
    $payments_res = pg_query($dbconn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style> 
        body { background-color: #f8f9fa; color: #212529; } 
        .card { background-color: #ffffff; border: 1px solid #dee2e6; color: #212529; box-shadow: 0 2px 4px rgba(0,0,0,0.05); } 
        .table { color: #212529; }
        .table code { color: #d63384; font-weight: 600; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark">Manage Payments</h3>
        <a href="dashboard.php" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to Admin</a>
    </div>
    <div class="card p-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="m-0 fw-bold">Payment History</h5>
            <!-- Search Bar -->
            <form method="GET" action="payments.php" class="d-flex" style="max-width: 350px; width: 100%;">
                <input type="text" name="search" class="form-control form-control-sm bg-white text-dark border-secondary me-2" placeholder="Search Username or Txn ID..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="btn btn-sm btn-primary me-1"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                <?php if($search_query !== ''): ?>
                    <a href="payments.php" class="btn btn-sm btn-secondary" title="Reset Search"><i class="fa-solid fa-xmark"></i></a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Txn ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($payments_res && pg_num_rows($payments_res) > 0): ?>
                        <?php while($p = pg_fetch_assoc($payments_res)): ?>
                        <tr>
                            <td>#<?php echo $p['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($p['username']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($p['txn_id']); ?></code></td>
                            <td>₹<?php echo number_format($p['amount'], 2); ?></td>
                            <td><span class="badge bg-<?php echo ($p['status']=='Approved')?'success':(($p['status']=='Rejected')?'danger':'warning'); ?>"><?php echo htmlspecialchars($p['status']); ?></span></td>
                            <td>
                                <?php if($p['status'] == 'Pending'): ?>
                                    <a href="?approve=<?php echo $p['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                    <a href="?reject=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                                <?php else: ?>
                                    <span class="text-muted">Processed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No payments found!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
