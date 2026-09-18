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

// Delete Free Claim Record
if (isset($_GET['delete_claim_id'])) {
    $del_id = (int)$_GET['delete_claim_id'];
    $del_res = pg_query_params($dbconn, "DELETE FROM free_claims WHERE id = $1", array($del_id));
    if ($del_res) {
        $msg = "Claim record #{$del_id} deleted successfully!";
    } else {
        $msg = "Failed to delete claim record.";
    }
}

// Fetch all Free Claims
$claims_res = pg_query($dbconn, "SELECT free_claims.*, users.username 
                                 FROM free_claims 
                                 LEFT JOIN users ON free_claims.user_id::integer = users.id 
                                 ORDER BY free_claims.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Claims - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style> 
        body { background-color: #f8f9fa; color: #212529; } 
        .card { background-color: #ffffff; border: 1px solid #dee2e6; color: #212529; box-shadow: 0 2px 4px rgba(0,0,0,0.05); } 
        .table { color: #212529; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 border-bottom border-danger">
  <div class="container">
    <a class="navbar-brand text-danger fw-bold fs-4" href="dashboard.php">Admin Panel</a>
    <div>
      <a href="dashboard.php" class="btn btn-sm btn-outline-light me-2">Users & Notice</a>
      <a href="orders.php" class="btn btn-sm btn-outline-light me-2">Orders</a>
      <a href="payments.php" class="btn btn-sm btn-outline-light me-2">Payments</a>
      <a href="free_claims.php" class="btn btn-sm btn-warning fw-bold me-2">Free Claims</a>
      <a href="../logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
    <?php if($msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-3">
        <h5 class="m-0 fw-bold mb-3"><i class="fa-solid fa-gift text-warning me-2"></i> Free View Claimed Links</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Instagram Reel / Video Link</th>
                        <th>IP Address</th>
                        <th>Claimed Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($claims_res && pg_num_rows($claims_res) > 0): ?>
                        <?php while($c = pg_fetch_assoc($claims_res)): ?>
                        <tr>
                            <td>#<?php echo $c['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($c['username'] ?? 'User #'.$c['user_id']); ?></strong></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($c['link']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Open Link
                                </a>
                            </td>
                            <td><code><?php echo htmlspecialchars($c['ip_address']); ?></code></td>
                            <td><?php echo htmlspecialchars($c['last_claimed_at']); ?></td>
                            <td>
                                <a href="free_claims.php?delete_claim_id=<?php echo $c['id']; ?>" onclick="return confirm('Delete this claim history?')" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash me-1"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No free claims found!</td>
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
