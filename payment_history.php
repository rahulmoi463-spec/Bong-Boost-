<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<h2 style='color:red;'>User Not Logged In (Session empty)</h2>";
    exit();
}

$user_id = $_SESSION['user_id'];

// কানেকশন অবজেক্ট খোঁজা
$db_obj = NULL;
if (isset($conn)) { $db_obj = $conn; }
elseif (isset($db)) { $db_obj = $db; }
elseif (isset($pdo)) { $db_obj = $pdo; }

echo "<div style='background:#111; color:#fff; padding:20px; font-family:monospace;'>";
echo "<h2>--- DATABASE DEBUG MODE ---</h2>";
echo "<p><strong>Logged-in User ID:</strong> " . htmlspecialchars($user_id) . "</p>";

if ($db_obj instanceof PDO) {
    echo "<p style='color:green;'>Connected via PDO!</p>";
    
    echo "<hr><h3>1. Payments Table Sample Data:</h3>";
    $stmt1 = $db_obj->prepare("SELECT * FROM payments WHERE user_id = :uid LIMIT 5");
    $stmt1->execute([':uid' => $user_id]);
    $p_data = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>"; print_r($p_data); echo "</pre>";

    echo "<hr><h3>2. Orders Table Sample Data:</h3>";
    $stmt2 = $db_obj->prepare("SELECT * FROM orders WHERE user_id = :uid LIMIT 5");
    $stmt2->execute([':uid' => $user_id]);
    $o_data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>"; print_r($o_data); echo "</pre>";

} else if (function_exists('mysqli_query') && $db_obj) {
    echo "<p style='color:green;'>Connected via MySQLi!</p>";

    echo "<hr><h3>1. Payments Table Sample Data:</h3>";
    $res1 = mysqli_query($db_obj, "SELECT * FROM payments WHERE user_id = '$user_id' LIMIT 5");
    $p_data = [];
    if ($res1) { while($r = mysqli_fetch_assoc($res1)) { $p_data[] = $r; } }
    echo "<pre>"; print_r($p_data); echo "</pre>";

    echo "<hr><h3>2. Orders Table Sample Data:</h3>";
    $res2 = mysqli_query($db_obj, "SELECT * FROM orders WHERE user_id = '$user_id' LIMIT 5");
    $o_data = [];
    if ($res2) { while($r = mysqli_fetch_assoc($res2)) { $o_data[] = $r; } }
    echo "<pre>"; print_r($o_data); echo "</pre>";

} else {
    echo "<h3 style='color:red;'>Could not detect active database connection variable!</h3>";
    echo "<p>Defined variables: "; print_r(array_keys(get_defined_vars())); echo "</p>";
}
echo "</div>";
?>
