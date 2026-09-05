<?php
session_start();

// ভারতীয় টাইমজোন সেট (IST - Indian Standard Time)
date_default_timezone_set('Asia/Kolkata');

require_once 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// সঠিক ডাটাবেস কানেকশন অবজেক্ট নির্ধারণ
$db = NULL;
if (isset($dbconn) && $dbconn) { $db = $dbconn; }
elseif (isset($conn) && $conn) { $db = $conn; }

function fetchTransactions($db, $user_id) {
    if (!$db) return [];

    $rows = [];

    // ১. সফল ডিপোজিট ডাটা ফেচিং (Credit with Txn ID)
    $sql_payments = "SELECT amount, txn_id, created_at, status FROM payments WHERE user_id::text = $1 AND LOWER(status::text) IN ('approved', 'completed', 'success') ORDER BY created_at DESC";
    $res1 = pg_query_params($db, $sql_payments, array((string)$user_id));

    if ($res1) {
        while ($r = pg_fetch_assoc($res1)) {
            $txn_text = !empty($r['txn_id']) ? 'Deposit via UPI (Txn: ' . $r['txn_id'] . ')' : 'Deposit via UPI';
            $rows[] = [
                'type' => 'Credit',
                'amount' => $r['amount'],
                'description' => $txn_text,
                'status' => 'Credit',
                'created_at' => $r['created_at']
            ];
        }
    }

    // ২. অর্ডার ও রিফান্ড ডাটা ফেচিং (Debit & Refund Credit)
    $sql_orders = "SELECT id, charge AS amount, created_at, status FROM orders WHERE user_id::text = $1 ORDER BY created_at DESC";
    $res2 = pg_query_params($db, $sql_orders, array((string)$user_id));

    if ($res2) {
        while ($r = pg_fetch_assoc($res2)) {
            $st = strtolower($r['status']);
            if ($st == 'cancelled' || $st == 'refunded') {
                $rows[] = [
                    'type' => 'Credit',
                    'amount' => $r['amount'],
                    'description' => 'Order Refunded',
                    'status' => 'Refund',
                    'created_at' => $r['created_at']
                ];
            } else {
                $rows[] = [
                    'type' => 'Debit',
                    'amount' => $r['amount'],
                    'description' => 'New Order Purchase',
                    'status' => 'Debit',
                    'created_at' => $r['created_at']
                ];
            }
        }
    }

    // তারিখ অনুযায়ী সাজানো
    usort($rows, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    return $rows;
}

$all_rows = fetchTransactions($db, $user_id);

// প্যাজিনেশন লজিক (প্রতি পেজে ১০ টি করে)
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$total_items = count($all_rows);
$total_pages = ceil($total_items / $limit);
$offset = ($page - 1) * $limit;

$rows = array_slice($all_rows, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - Bong Boost</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b0e14; color: #fff; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 12px; margin: 0; }
        .container { max-width: 800px; margin: 0 auto; }
        .back-btn { display: inline-block; color: #3498db; text-decoration: none; margin-bottom: 12px; font-weight: 600; font-size: 13px; }
        h2 { font-size: 18px; margin-top: 0; margin-bottom: 12px; }
        .history-card { background: #161b22; border-radius: 8px; padding: 8px; border: 1px solid #2d3748; overflow-x: auto; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 8px 10px; border-bottom: 1px solid #2d3748; font-size: 12px; white-space: nowrap; }
        th { color: #8b949e; background: #0d1117; font-weight: 600; text-transform: uppercase; font-size: 11px; }
        
        .credit { color: #2ecc71; font-weight: bold; } 
        .debit { color: #e74c3c; font-weight: bold; }  
        
        .type-badge { padding: 3px 7px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; }
        .bg-credit { background: rgba(46, 204, 113, 0.15); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.3); }
        .bg-debit { background: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }

        /* Pagination Style */
        .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; padding: 0 4px; }
        .page-btn { background: #21262d; color: #c9d1d9; border: 1px solid #30363d; padding: 5px 12px; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: 600; }
        .page-btn.disabled { opacity: 0.4; pointer-events: none; }
        .page-info { font-size: 11px; color: #8b949e; }
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
                            <?php 
                                // UTC সময়কে IST সময়ে কনভার্ট করা
                                $date = new DateTime($row['created_at'], new DateTimeZone('UTC'));
                                $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                $formatted_date = $date->format('d M Y, h:i A');
                            ?>
                            <tr>
                                <td><?php echo $formatted_date; ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Credit' || $row['status'] == 'Refund'): ?>
                                        <span class="type-badge bg-credit">Credit</span>
                                    <?php else: ?>
                                        <span class="type-badge bg-debit">Debit</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td class="<?php echo ($row['status'] == 'Credit' || $row['status'] == 'Refund') ? 'credit' : 'debit'; ?>">
                                    <?php echo ($row['status'] == 'Credit' || $row['status'] == 'Refund') ? '+' : '-'; ?>₹<?php echo number_format($row['amount'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #8b949e; padding: 15px; font-size: 12px;">No transaction history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <a href="?page=<?php echo $page - 1; ?>" class="page-btn <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <i class="fa-solid fa-chevron-left"></i> Prev
                </a>
                <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                <a href="?page=<?php echo $page + 1; ?>" class="page-btn <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
