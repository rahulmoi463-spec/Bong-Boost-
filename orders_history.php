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

$refill_success = "";
$refill_error = "";

// Handle Refill Request Safely
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_refill'])) {
    $order_id = (int)$_POST['order_id'];
    
    $ord_check = pg_query_params($dbconn, "SELECT * FROM orders WHERE id = $1 AND user_id = $2", array($order_id, $user_id));
    
    if ($ord_check && pg_num_rows($ord_check) > 0) {
        $ord = pg_fetch_assoc($ord_check);
        if ($ord['status'] == 'Refill Requested') {
            $refill_error = "Refill request is already pending!";
        } else {
            pg_query_params($dbconn, "UPDATE orders SET status = 'Refill Requested' WHERE id = $1", array($order_id));
            $refill_success = "Refill request submitted successfully!";
        }
    } else {
        $refill_error = "Invalid order details.";
    }
}

// Fetch orders safely directly from orders table
$orders_query = pg_query_params($dbconn, "SELECT * FROM orders WHERE user_id = $1 ORDER BY id DESC", array($user_id));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders History - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background: #0f172a;
            background-image: 
                radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.25) 0px, transparent 50%),
                radial-gradient(at 90% 20%, rgba(217, 70, 239, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(14, 165, 233, 0.25) 0px, transparent 50%);
            background-attachment: fixed;
            color: #1e293b; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }
        
        .navbar { 
            background: rgba(15, 23, 42, 0.85) !important; 
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }

        .table {
            vertical-align: middle;
        }

        .btn-whatsapp { 
            background: #25D366; 
            color: #fff; 
            font-weight: 700; 
            border-radius: 10px; 
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }
        .btn-whatsapp:hover { background-color: #1da851; color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold text-white fs-3" href="dashboard.php">Bong Boost</a>
    <div class="d-flex align-items-center flex-wrap gap-2">
      <a href="dashboard.php" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
      <a href="add_fund.php" class="btn btn-sm btn-success"><i class="fa-solid fa-plus me-1"></i> Add Fund</a>
      <a href="https://wa.me/917718231993?text=Hello%20Bong%20Boost%20Support" target="_blank" class="btn btn-sm btn-whatsapp"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Support</a>
      <a href="logout.php" class="btn btn-sm btn-danger"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="container pb-5">
    <div class="glass-card p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Orders History</h4>
            <div class="col-md-4 col-12">
                <input type="text" id="orderSearchInput" class="form-control" placeholder="🔍 Search Order ID, Service...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle" id="ordersTable">
                <thead class="table-dark">
                    <tr>
                        <th>ORDER ID</th>
                        <th>SERVICE ID</th>
                        <th>SERVICE NAME</th>
                        <th>LINK</th>
                        <th>QTY</th>
                        <th>CHARGE</th>
                        <th style="min-width: 160px;">STATUS & PROGRESS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders_query && pg_num_rows($orders_query) > 0): ?>
                        <?php while ($row = pg_fetch_assoc($orders_query)): ?>
                            <tr>
                                <td><strong>#<?php echo $row['id']; ?></strong></td>
                                
                                <td>
                                    <span class="badge bg-primary fs-6 px-2 py-1">
                                        <?php echo !empty($row['service_id']) ? $row['service_id'] : 'N/A'; ?>
                                    </span>
                                </td>
                                
                                <td><?php echo htmlspecialchars(!empty($row['service_name']) ? $row['service_name'] : ('Service #' . $row['service_id'])); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 150px;">
                                        <?php echo htmlspecialchars($row['link']); ?>
                                    </a>
                                </td>
                                <td><?php echo number_format($row['quantity']); ?></td>
                                <td class="text-success fw-bold">₹<?php echo number_format($row['charge'], 2); ?></td>
                                <td>
                                    <?php 
                                    $st = strtolower(trim($row['status']));
                                    $percent = 10;
                                    $bg_color = 'bg-warning';
                                    $badge_class = 'bg-warning text-dark';

                                    if ($st == 'completed') {
                                        $percent = 100;
                                        $bg_color = 'bg-success';
                                        $badge_class = 'bg-success';
                                    } elseif ($st == 'processing' || $st == 'in progress') {
                                        $percent = 50;
                                        $bg_color = 'bg-info progress-bar-striped progress-bar-animated';
                                        $badge_class = 'bg-info text-dark';
                                    } elseif ($st == 'canceled' || $st == 'cancelled') {
                                        $percent = 100;
                                        $bg_color = 'bg-danger';
                                        $badge_class = 'bg-danger';
                                    } elseif ($st == 'refill requested') {
                                        $percent = 75;
                                        $bg_color = 'bg-primary progress-bar-striped progress-bar-animated';
                                        $badge_class = 'bg-primary';
                                    }
                                    ?>
                                    <div>
                                        <span class="badge <?php echo $badge_class; ?> mb-1"><?php echo htmlspecialchars($row['status']); ?></span>
                                        <div class="progress" style="height: 6px; background-color: #e2e8f0; border-radius: 10px;">
                                            <div class="progress-bar <?php echo $bg_color; ?>" role="progressbar" style="width: <?php echo $percent; ?>%; border-radius: 10px;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="request_refill" class="btn btn-sm btn-outline-primary fw-bold" <?php echo ($row['status'] == 'Refill Requested') ? 'disabled' : ''; ?>>
                                            <i class="fa-solid fa-rotate me-1"></i> Refill
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr id="noOrdersRow">
                            <td colspan="8" class="text-center py-4 text-muted fw-bold">No orders found!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('orderSearchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#ordersTable tbody tr');

        rows.forEach(row => {
            if (row.id === 'noOrdersRow') return;
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    <?php if($refill_success): ?>
        Swal.fire({
            icon: 'success',
            title: 'Submitted!',
            text: '<?php echo $refill_success; ?>',
            confirmButtonColor: '#10b981'
        });
    <?php endif; ?>

    <?php if($refill_error): ?>
        Swal.fire({
            icon: 'error',
            title: 'Notice',
            text: '<?php echo $refill_error; ?>',
            confirmButtonColor: '#ef4444'
        });
    <?php endif; ?>
</script>
</body>
</html>
