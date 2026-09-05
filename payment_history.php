<?php
session_start();

require_once 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ডাটাবেস কানেকশন হ্যান্ডলার
$db = NULL;
if (isset($dbconn) && $dbconn) { $db = $dbconn; }
elseif (isset($conn) && $conn) { $db = $conn; }

function fetchTransactions($db, $user_id) {
    if (!$db) return [];

    $rows = [];

    // ১. পেমেন্টস বা ডিপোজিট তথ্য ( Approved / Completed / Success )
    $sql_payments = "SELECT amount, txn_id, created_at, status FROM payments WHERE user_id::text = $1 AND LOWER(status::text) IN ('approved', 'completed', 'success') ORDER BY created_at DESC";
    
    // ২. অর্ডার তথ্য
    $sql_orders = "SELECT id, charge AS amount, created_at, status FROM orders WHERE user_id::text = $1 ORDER BY created_at DESC";

    if (function_exists('pg_query_params') && is_resource($db)) {
        // PostgreSQL সরাসরি ড্রাইভার
        $res1 = pg_query_params($db, $sql_payments, array((string)$user_id));
        if ($res1) {
            while ($r = pg_fetch_assoc($res1)) {
                $rows[] = [
                    'type' => 'Deposit',
                    'amount' => $r['amount'],
                    'description' => 'Payment Received' . (!empty($r['txn_id']) ? ' (Txn: ' . $r['txn_id'] . ')' : ''),
                    'status' => 'Credit',
                    'created_at' => $r['created_at']
                ];
            }
        }

        $res2 = pg_query_params($db, $sql_orders, array((string)$user_id));
        if ($res2) {
            while ($r = pg_fetch_assoc($res2)) {
                $st = strtolower($r['status']);
                if ($st == 'cancelled' || $st == 'refunded') {
                    $rows[] = [
                        'type' => 'Refund',
                        'amount' => $r['amount'],
                        'description' => 'Refund for Order #' . $r['id'],
                        'status' => 'Refund',
                        'created_at' => $r['created_at']
                    ];
                } else {
                    $rows[] = [
                        'type' => 'Order',
                        'amount' => $r['amount'],
                        'description' => 'Order #' . $r['id'],
                        'status' => 'Debit',
                        'created_at' => $r['created_at']
                    ];
                }
            }
        }
    } else if ($db instanceof PDO) {
        // PDO ড্রাইভার ব্যাকআপ
        $stmt1 = $db->prepare("SELECT amount, txn_id, created_at FROM payments WHERE user_id::text = :uid AND LOWER(status::text) IN ('approved', 'completed', 'success')");
        $stmt1->execute([':uid' => (string)$user_id]);
        while ($r = $stmt1->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = [
                'type' => 'Deposit',
                'amount' => $r['amount'],
                'description' => 'Payment Received' . (!empty($r['txn_id']) ? ' (Txn: ' . $r['txn_id'] . ')' : ''),
                'status' => 'Credit',
                'created_at' => $r['created_at']
            ];
        }

        $stmt2 = $db->prepare("SELECT id, charge AS amount, created_at, status FROM orders WHERE user_id::text = :uid");
        $stmt2->execute([':uid' => (string)$user_id]);
        while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $st = strtolower($r['status']);
            if ($st == 'cancelled' || $st == 'refunded') {
                $rows[] = [
                    'type' => 'Refund',
                    'amount' => $r['amount'],
                    'description' => 'Refund for Order #' . $r['id'],
                    'status' => 'Refund',
                    'created_at' => $r['created_at']
                ];
            } else {
                $rows[] = [
                    'type' => 'Order',
                    'amount' => $r['amount'],
                    'description' => 'Order #' . $r['id'],
                    'status' => 'Debit',
                    'created_at' => $r['created_at']
                ];
            }
        }
    }

    // তারিখ অনুযায়ী নতুন লেনদেন আগে সাজানো (Sort Newest First)
    usort($rows, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    return $rows;
}

$rows = fetchTransactions($db, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - Bong Boost</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b0e14; color: #fff; font-family: sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .back-btn { display: inline-block; color: #3498db; text-decoration: none; margin-bottom: 20px; font-weight: bold; }
        .history-card { background: #161b22; border-radius: 10px; padding: 15px; border: 1px solid #2d3748; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px; border-bottom: 1px solid #2d3748; font-size: 14px; }
        th { color: #8b949e; background: #0d1117; }
        .credit { color: #2ecc71; font-weight: bold; } 
        .debit { color: #e74c3c; font-weight: bold; }  
        .type-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .bg-credit { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .bg-debit { background: rgba(231, 76, 60, 0.2); color: #e74c3c; }
        .bg-refund { background: rgba(52, 152, 219, 0.2); color: #3498db; }
    </style>
</head>
<body>

    <div class="container">
        <a href="dashboard.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        <h2><i class="fa-solid fa-receipt text-success"></i> Payment & Wallet History</h2>

        <div class="history-card">
            <table>
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Credit'): ?>
                                        <span class="type-badge bg-credit">Deposit</span>
                                    <?php elseif ($row['status'] == 'Debit'): ?>
                                        <span class="type-badge bg-debit">Order Debit</span>
                                    <?php else: ?>
                                        <span class="type-badge bg-refund">Refund</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td class="<?php echo ($row['status'] == 'Credit' || $row['status'] == 'Refund') ? 'credit' : 'debit'; ?>">
                                    <?php echo ($row['status'] == 'Credit' || $row['status'] == 'Refund') ? '+' : '-'; ?> 
                                    ₹<?php echo number_format($row['amount'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #8b949e; padding: 20px;">No transaction history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
