<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'config/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_res = pg_query_params($dbconn, "SELECT discount_percent FROM users WHERE id = $1", array($user_id));
$user_data = pg_fetch_assoc($user_res);
$user_discount = (int)($user_data['discount_percent'] ?? 0);

$services = pg_query($dbconn, "SELECT * FROM services WHERE status = 'active' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0b0f19; color: #fff; font-family: sans-serif; padding: 20px; }
        .card { background: #151d2a; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); }
        .table-dark { background: #151d2a; }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="fa-solid fa-list-check text-info me-2"></i>Service List</h4>
            <a href="dashboard.php" class="btn btn-sm btn-outline-light">Dashboard</a>
        </div>
        <?php if($user_discount > 0): ?>
            <div class="alert alert-success py-2">
                <i class="fa-solid fa-gift me-2"></i>You have a special <strong><?php echo $user_discount; ?>% Discount</strong> enabled on all services!
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Rate per 1000 (₹)</th>
                        <th>Min / Max</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = pg_fetch_assoc($services)): 
                        $original_price = (float)$row['price'];
                        $discounted_price = $user_discount > 0 ? $original_price - ($original_price * ($user_discount / 100)) : $original_price;
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name'] ?? $row['service_name'] ?? ''); ?></td>
                        <td>
                            <?php if($user_discount > 0): ?>
                                <span class="text-decoration-line-through text-danger me-2">₹<?php echo number_format($original_price, 2); ?></span>
                                <span class="badge bg-success">₹<?php echo number_format($discounted_price, 2); ?> (<?php echo $user_discount; ?>% OFF)</span>
                            <?php else: ?>
                                <span class="fw-bold">₹<?php echo number_format($original_price, 2); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo ($row['min'] ?? '100') . ' / ' . ($row['max'] ?? '10000'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
