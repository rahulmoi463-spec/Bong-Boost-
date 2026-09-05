<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<h2 style='color:red;'>User Not Logged In!</h2>";
    exit();
}

$user_id = $_SESSION['user_id'];

echo "<div style='background:#111; color:#fff; padding:20px; font-family:monospace;'>";
echo "<h2>--- SUPABASE / POSTGRES DIAGNOSTIC ---</h2>";
echo "<p><strong>Logged-in User ID:</strong> " . htmlspecialchars($user_id) . "</p>";

// $dbconn অথবা $conn চেক
$db = NULL;
if (isset($dbconn) && $dbconn) { $db = $dbconn; }
elseif (isset($conn) && $conn) { $db = $conn; }

if (!$db) {
    echo "<h3 style='color:red;'>Database Connection Object ($dbconn / $conn) Not Found!</h3>";
    exit();
}

// 1. Payments Table Query
echo "<hr><h3>1. Payments Data for User ID {$user_id}:</h3>";
$res1 = pg_query_params($db, "SELECT * FROM payments WHERE user_id::text = $1 LIMIT 5", array((string)$user_id));
if ($res1) {
    $p_data = pg_fetch_all($res1);
    echo "<pre>"; print_r($p_data ?: "NO PAYMENTS FOUND FOR THIS USER_ID"); echo "</pre>";
} else {
    echo "<p style='color:red;'>Payments Query Error: " . pg_last_error($db) . "</p>";
}

// 2. Orders Table Query
echo "<hr><h3>2. Orders Data for User ID {$user_id}:</h3>";
$res2 = pg_query_params($db, "SELECT * FROM orders WHERE user_id::text = $1 LIMIT 5", array((string)$user_id));
if ($res2) {
    $o_data = pg_fetch_all($res2);
    echo "<pre>"; print_r($o_data ?: "NO ORDERS FOUND FOR THIS USER_ID"); echo "</pre>";
} else {
    echo "<p style='color:red;'>Orders Query Error: " . pg_last_error($db) . "</p>";
}

echo "</div>";
?>
