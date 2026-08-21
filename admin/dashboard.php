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

// Update Notice
if (isset($_POST['update_notice'])) {
    $content = trim($_POST['notice']);
    $res = pg_query_params($dbconn, "UPDATE notices SET content = $1 WHERE id = 1", array($content));
    if ($res) {
        $msg = "Notice updated successfully!";
    }
}

// User Actions (Balance Update, Ban, Delete)
if (isset($_POST['update_balance'])) {
    $u_id = (int)$_POST['user_id'];
    $bal = (float)$_POST['balance'];
    pg_query_params($dbconn, "UPDATE users SET balance = $1 WHERE id = $2", array($bal, $u_id));
    $msg = "User balance updated!";
}
if (isset($_GET['ban'])) {
    $u_id = (int)$_GET['ban'];
    pg_query_params($dbconn, "UPDATE users SET status = 'banned' WHERE id = $1", array($u_id));
    $msg = "User banned!";
}
if (isset($_GET['unban'])) {
    $u_id = (int)$_GET['unban'];
    pg_query_params($dbconn, "UPDATE users SET status = 'active' WHERE id = $1", array($u_id));
    $msg = "User unbanned!";
}
if (isset($_GET['delete_user'])) {
    $u_id = (int)$_GET['delete_user'];
    pg_query_params($dbconn, "DELETE FROM users WHERE id = $1", array($u_id));
    $msg = "User deleted!";
}

// Delete Free Claim Log
if (isset($_GET['delete_claim'])) {
    $c_id = (int)$_GET['delete_claim'];
    pg_query_params($dbconn, "DELETE FROM free_claims WHERE id = $1", array($c_id));
    $msg = "Free Claim log deleted successfully!";
}

// Reset User Password (Default: 123456)
if (isset($_GET['reset_pass'])) {
    $u_id = (int)$_GET['reset_pass'];
    $new_pass = password_hash("123456", PASSWORD_DEFAULT);
    pg_query_params($dbconn, "UPDATE users SET password = $1 WHERE id = $2", array($new_pass, $u_id));
    $msg = "Password reset to '123456' for User ID #" . $u_id;
}

$tab = $_GET['tab'] ?? 'main';
$search_query = "";

// Handle User Search using PostgreSQL ILIKE
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = trim($_GET['search']);
    $search_param = '%' . $search_query . '%';
    
    $query = "SELECT * FROM users WHERE username ILIKE $1 OR mobile ILIKE $1 ORDER BY id DESC";
    $users_res = pg_query_params($dbconn, $query, array($search_param));
} else {
    $users_res = pg_query($dbconn, "SELECT * FROM users ORDER BY id DESC");
}

$notice_res = pg_query($dbconn, "SELECT content FROM notices WHERE id = 1");
$notice = pg_fetch_assoc($notice_res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style> 
    body { background-color: #f8f9fa; color: #212529; } 
    .card { background-color: #ffffff; border: 1px solid #dee2e6; color: #212529; box-shadow: 0 2px 4px rgba(0,0,0,0.05); } 
    .table { color: #212529; }
    .table-striped>tbody>tr:nth-of-type(odd)>* { --bs-table-bg-type: #f8f9fa; color: #212529; }
    .form-control { background-color: #ffffff !important; color: #212529 !important; border: 1px solid #ced4da !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 border-bottom border-danger">
  <div class="container">
    <a class="navbar-brand text-danger fw-bold fs-4" href="dashboard.php">Admin Panel</a>
    <div>
      <a href="dashboard.php" class="btn btn-sm <?php echo ($tab=='main')?'btn-light':'btn-outline-light'; ?> me-2">Users & Notice</a>
      <a href="orders.php" class="btn btn-sm btn-outline-light me-2">Orders</a>
      <a href="payments.php" class="btn btn-sm btn-outline-light me-2">Payments</a>
      <a href="dashboard.php?tab=free_claims" class="btn btn-sm <?php echo ($tab=='free_claims')?'btn-warning':'btn-outline-warning'; ?> fw-bold me-2"><i class="fa-solid fa-gift me-1"></i> Free Claims</a>
      <a href="sync_services.php" class="btn btn-sm btn-warning me-2">Sync Services (30%)</a>
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

    <?php if ($tab == 'free_claims'): ?>
        <!-- Free Claims Tab -->
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0 text-warning"><i class="fa-solid fa-gift me-2"></i>Free Views Claim Requests</h5>
                <a href="dashboard.php" class="btn btn-sm btn-outline-dark"><i class="fa-solid fa-arrow-left me-1"></i> Back to Users</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#ID</th>
                            <th>User Name</th>
                            <th>IP Address</th>
                            <th>Instagram Reel Link</th>
                            <th>Claimed Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $claims_res = pg_query($dbconn, "SELECT fc.*, u.username FROM free_claims fc LEFT JOIN users u ON fc.user_id = u.id ORDER BY fc.id DESC");
                        if ($claims_res && pg_num_rows($claims_res) > 0):
                            while ($c = pg_fetch_assoc($claims_res)):
                        ?>
                        <tr>
                            <td>#<?php echo $c['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($c['username'] ?? 'User #'.$c['user_id']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($c['ip_address']); ?></code></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($c['link']); ?>" target="_blank" class="btn btn-sm btn-outline-info text-dark me-1">Open Reel</a>
                                <button class="btn btn-sm btn-secondary" onclick="navigator.clipboard.writeText('<?php echo htmlspecialchars($c['link']); ?>'); alert('Link Copied!');">Copy Link</button>
                            </td>
                            <td><?php echo date("d M Y, h:i A", strtotime($c['last_claimed_at'])); ?></td>
                            <td>
                                <a href="?tab=free_claims&delete_claim=<?php echo $c['id']; ?>" onclick="return confirm('Delete this claim history?')" class="btn btn-sm btn-danger">Delete Log</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                        <tr><td colspan="6" class="text-muted py-4">No free claim logs found yet!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>
        <!-- Default Users & Notice Tab -->
        <div class="card p-3 mb-4">
            <h5>Update Notice Board</h5>
            <form method="POST">
                <textarea name="notice" class="form-control border-secondary mb-2" rows="3"><?php echo htmlspecialchars($notice['content'] ?? ''); ?></textarea>
                <button type="submit" name="update_notice" class="btn btn-primary btn-sm">Save Notice</button>
            </form>
        </div>

        <div class="card p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-2 mb-md-0">All Registered Users</h5>
                <!-- User Search Bar -->
                <form method="GET" action="dashboard.php" class="d-flex" style="max-width: 350px; width: 100%;">
                    <input type="text" name="search" class="form-control form-control-sm border-secondary me-2" placeholder="Search Username or Mobile..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="btn btn-sm btn-primary me-1"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <?php if(!empty($search_query)): ?>
                        <a href="dashboard.php" class="btn btn-sm btn-secondary" title="Reset Search"><i class="fa-solid fa-xmark"></i></a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Mobile</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users_res && pg_num_rows($users_res) > 0): ?>
                            <?php while($u = pg_fetch_assoc($users_res)): ?>
                            <tr>
                                <td>#<?php echo $u['id']; ?></td>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td><?php echo htmlspecialchars($u['mobile']); ?></td>
                                <td>
                                    <form method="POST" class="d-flex" style="max-width: 150px;">
                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                        <input type="number" step="0.01" name="balance" value="<?php echo $u['balance']; ?>" class="form-control form-control-sm me-1">
                                        <button type="submit" name="update_balance" class="btn btn-sm btn-success">Save</button>
                                    </form>
                                </td>
                                <td><span class="badge bg-<?php echo ($u['status']=='active')?'success':'danger'; ?>"><?php echo htmlspecialchars($u['status']); ?></span></td>
                                <td>
                                    <?php if($u['status']=='active'): ?>
                                        <a href="?ban=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning me-1">Ban</a>
                                    <?php else: ?>
                                        <a href="?unban=<?php echo $u['id']; ?>" class="btn btn-sm btn-info me-1">Unban</a>
                                    <?php endif; ?>
                                    <a href="?reset_pass=<?php echo $u['id']; ?>" onclick="return confirm('Reset password to 123456?')" class="btn btn-sm btn-warning me-1">Reset Pass</a>
                                    <a href="?delete_user=<?php echo $u['id']; ?>" onclick="return confirm('Delete user?')" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No users found matching "<?php echo htmlspecialchars($search_query); ?>"</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
