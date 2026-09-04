<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_GET['txn_id'])) {
    echo json_encode(['status' => 'Pending']);
    exit();
}

$user_id = $_SESSION['user_id'];
$txn_id = trim($_GET['txn_id']);

// Check payment status from PostgreSQL
$res = pg_query_params($dbconn, "SELECT status FROM payments WHERE user_id = $1 AND txn_id = $2 ORDER BY id DESC LIMIT 1", array($user_id, $txn_id));

if ($res && pg_num_rows($res) > 0) {
    $row = pg_fetch_assoc($res);
    echo json_encode(['status' => $row['status']]);
} else {
    echo json_encode(['status' => 'Pending']);
}
?>
