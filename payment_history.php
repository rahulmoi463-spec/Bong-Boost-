<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$db = NULL;
if (isset($dbconn) && $dbconn) { $db = $dbconn; }
elseif (isset($conn) && $conn) { $db = $conn; }

echo "<div style='background:#000; color:#0f0; padding:15px; font-family:monospace;'>";
echo "DEBUG LOGGED IN USER: " . htmlspecialchars($user_id) . "<br>";

if ($db) {
    // 1. Payments Check
    $res1 = pg_query_params($db, "SELECT * FROM payments WHERE user_id::text = $1 AND LOWER(status::text) IN ('approved', 'completed', 'success')", array((string)$user_id));
    $p_rows = $res1 ? pg_fetch_all($res1) : [];
    echo "Approved Payments Count: " . (is_array($p_rows) ? count($p_rows) : 0) . "<br>";

    // 2. Orders Check
    $res2 = pg_query_params($db, "SELECT * FROM orders WHERE user_id::text = $1", array((string)$user_id));
    $o_rows = $res2 ? pg_fetch_all($res2) : [];
    echo "Orders Count: " . (is_array($o_rows) ? count($o_rows) : 0) . "<br>";

    if (!$res1) echo "Payment Query Error: " . pg_last_error($db) . "<br>";
    if (!$res2) echo "Orders Query Error: " . pg_last_error($db) . "<br>";
} else {
    echo "DATABASE CONNECTION FAILED!<br>";
}
echo "</div>";
?>
