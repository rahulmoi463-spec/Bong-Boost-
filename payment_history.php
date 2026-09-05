<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<h2 style='color:red;'>User Not Logged In (Session empty)</h2>";
    exit();
}

$user_id = $_SESSION['user_id'];

echo "<div style='background:#111; color:#fff; padding:20px; font-family:monospace;'>";
echo "<h2>--- DATABASE DEBUG MODE ---</h2>";
echo "<p><strong>Logged-in User ID:</strong> " . htmlspecialchars($user_id) . "</p>";

if ($conn instanceof PDO) {
    // 1. Check Payments Table
    echo "<hr><h3>1. Payments Table Sample Data:</h3>";
    $stmt1 = $conn->prepare("SELECT * FROM payments WHERE user_id = :uid LIMIT 3");
    $stmt1->execute([':uid' => $user_id]);
    $p_data = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>"; print_r($p_data); echo "</pre>";

    // 2. Check Orders Table
    echo "<hr><h3>2. Orders Table Sample Data:</h3>";
    $stmt2 = $conn->prepare("SELECT * FROM orders WHERE user_id = :uid LIMIT 3");
    $stmt2->execute([':uid' => $user_id]);
    $o_data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>"; print_r($o_data); echo "</pre>";
} else {
    echo "<h3 style='color:red;'>PDO Connection Failed!</h3>";
}
echo "</div>";
?>
