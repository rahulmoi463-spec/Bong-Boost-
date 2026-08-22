<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $new_password = $_POST['password'];
    $email = $_SESSION['reset_email'];

    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND otp = ? AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
    $stmt->execute([$email, $otp]);
    $reset = $stmt->fetch();

    if ($reset) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update->execute([$hashed_password, $email]);

        $del = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $del->execute([$email]);

        unset($_SESSION['reset_email']);
        echo "<script>alert('পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে! লগইন করুন।'); window.location.href='login.php';</script>";
        exit();
    } else {
        $message = "ভুল অথবা মেয়াদোত্তীর্ণ OTP কোড!";
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { width: 90%; max-width: 380px; padding: 25px; background: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; text-align: center; color: #333; }
        input, button { width: 100%; padding: 12px; margin-top: 12px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; font-weight: bold; cursor: pointer; }
        .msg { color: #d9534f; background: #fdf7f7; padding: 8px; border-radius: 5px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Verify OTP & New Password</h2>
        <?php if($message) echo "<div class='msg'>$message</div>"; ?>
        <form method="POST">
            <input type="text" name="otp" required placeholder="৬ ডিজিটের OTP দিন">
            <input type="password" name="password" required placeholder="নতুন পাসওয়ার্ড দিন">
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>
