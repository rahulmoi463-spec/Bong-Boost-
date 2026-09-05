<?php
session_start();

require_once 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// সঠিক ডাটাবেস কানেকশন অবজেক্ট নির্ধারণ ($dbconn / $conn)
$db = NULL;
if (isset($dbconn) && $dbconn) { $db = $dbconn; }
elseif (isset($conn) && $conn) { $db = $conn; }

function fetchTransactions($db, $user_id) {
    if (!$db) return [];

    $sql = "
        (SELECT 'Deposit' AS type, amount, 'Payment Received' AS description, 'Credit' AS status, created_at 
         FROM payments 
         WHERE user_id::text = :uid1 AND LOWER(status::text) IN ('approved', 'completed', 'success'))
        UNION ALL
        (SELECT 'Order' AS type, charge AS amount, CONCAT('Order #', id) AS description, 'Debit' AS status, created_at 
         FROM orders 
         WHERE user_id::text = :uid2)
        UNION ALL
        (SELECT 'Refund' AS type, charge AS amount, CONCAT('Refund for Order #', id) AS description, 'Refund' AS status, created_at 
         FROM orders 
         WHERE user_id::text = :uid3 AND LOWER(status::text) IN ('cancelled', 'refunded'))
        ORDER BY created_at DESC
    ";

    // PDO ড্রাইভার
    if ($db instanceof PDO) {
        $stmt = $db->prepare($sql);
        $stmt->execute([':uid1' => (string)$user_id, ':uid2' => (string)$user_id, ':uid3' => (string)$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    // PostgreSQL (pg_query) ড্রাইভার
    else if (function_exists('pg_query_params') && is_resource($db)) {
        $pg_sql = "
            (SELECT 'Deposit' AS type, amount, 'Payment Received' AS description, 'Credit' AS status, created_at 
             FROM payments 
             WHERE user_id::text = $1 AND LOWER(status::text) IN ('approved', 'completed', 'success'))
            UNION ALL
            (SELECT 'Order' AS type, charge AS amount, CONCAT('Order #', id) AS description, 'Debit' AS status, created_at 
             FROM orders 
             WHERE user_id::text = $2)
            UNION ALL
            (SELECT 'Refund' AS type, charge AS amount, CONCAT('Refund for Order #', id) AS description, 'Refund' AS status, created_at 
             FROM orders 
             WHERE user_id::text = $3)
            ORDER BY created_at DESC
        ";
        $res = pg_query_params($db, $pg_sql, array($user_id, $user_id, $user_id));
        if ($res) {
            return pg_fetch_all($res) ?: [];
        }
    }
    // MySQLi ড্রাইভার
    else if (function_exists('mysqli_query')) {
        $raw_sql = "
            (SELECT 'Deposit' AS type, amount, 'Payment Received' AS description, 'Credit' AS status, created_at 
             FROM payments 
             WHERE user_id = '$user_id' AND LOWER(status) IN ('approved', 'completed', 'success'))
            UNION ALL
            (SELECT 'Order' AS type, charge AS amount, CONCAT('Order #', id) AS description, 'Debit' AS status, created_at 
             FROM orders 
             WHERE user_id = '$user_id')
            UNION ALL
            (SELECT 'Refund' AS type, charge AS amount, CONCAT('Refund for Order #', id) AS description, 'Refund' AS status, created_at 
             FROM orders 
             WHERE user_id = '$user_id' AND LOWER(status) IN ('cancelled', 'refunded'))
            ORDER BY created_at DESC
        ";
        $res = mysqli_query($db, $raw_sql);
        $data = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) { $data[] = $row; }
        }
        return $data;
    }

    return [];
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
