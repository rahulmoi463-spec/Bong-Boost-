function fetchTransactions($db, $user_id) {
    if (!$db) return [];

    $rows = [];

    // ১. পেমেন্টস টেবিল থেকে ক্রেডিট/ডেবিট ও এডমিন ট্রানজেকশন লোড
    $sql_payments = "SELECT amount, txn_id, created_at, status FROM payments WHERE user_id::text = $1 ORDER BY created_at DESC";
    $res1 = pg_query_params($db, $sql_payments, array((string)$user_id));

    if ($res1) {
        while ($r = pg_fetch_assoc($res1)) {
            $st = strtolower($r['status']);
            $txn_id = $r['txn_id'] ?? '';
            
            if (in_array($st, ['approved', 'completed', 'success'])) {
                // ADM- দিয়ে শুরু হলে সংক্ষেপে Admin Credit দেখাবে
                if (strpos($txn_id, 'ADM-') === 0) {
                    $txn_text = 'Admin Credit';
                } else {
                    $txn_text = !empty($txn_id) ? 'Deposit via UPI (Txn: ' . $txn_id . ')' : 'Deposit via UPI';
                }

                $rows[] = [
                    'type' => 'Credit',
                    'amount' => $r['amount'],
                    'description' => $txn_text,
                    'status' => 'Credit',
                    'created_at' => $r['created_at']
                ];
            } elseif ($st == 'refunded' || strpos($txn_id, 'RFD-') === 0) {
                $order_num = str_replace('RFD-', '', explode('-', $txn_id)[0] ?? '');
                $desc = !empty($order_num) ? 'Order Refund (#'.$order_num.')' : 'Order Refund';
                $rows[] = [
                    'type' => 'Credit',
                    'amount' => $r['amount'],
                    'description' => $desc,
                    'status' => 'Refund',
                    'created_at' => $r['created_at']
                ];
            } elseif ($st == 'debited') {
                $rows[] = [
                    'type' => 'Debit',
                    'amount' => $r['amount'],
                    'description' => 'Admin Debit',
                    'status' => 'Debit',
                    'created_at' => $r['created_at']
                ];
            }
        }
    }

    // ২. অর্ডার থেকে ডেবিট লোড
    $sql_orders = "SELECT id, charge AS amount, created_at, status FROM orders WHERE user_id::text = $1 ORDER BY created_at DESC";
    $res2 = pg_query_params($db, $sql_orders, array((string)$user_id));

    if ($res2) {
        while ($r = pg_fetch_assoc($res2)) {
            $st = strtolower($r['status']);
            if ($st != 'canceled' && $st != 'cancelled') {
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
