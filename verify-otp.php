<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $new_password = $_POST['password'];
    $email = $_SESSION['reset_email'];

    $res = pg_query_params($dbconn, "SELECT * FROM password_resets WHERE email = $1 AND otp = $2 AND expires_at > NOW() ORDER BY id DESC LIMIT 1", array($email, $otp));

    if ($res && pg_num_rows($res) > 0) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        pg_query_params($dbconn, "UPDATE users SET password = $1 WHERE email = $2", array($hashed_password, $email));
        pg_query_params($dbconn, "DELETE FROM password_resets WHERE email = $1", array($email));

        unset($_SESSION['reset_email']);
        echo "<script>alert('Password reset successful! Please login.'); window.location.href='login.php';</script>";
        exit();
    } else {
        $error = "Invalid or expired OTP code!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e0f2fe 0%, #f1f5f9 100%); font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px 15px; }
        .auth-card { background: #ffffff; border-radius: 20px; box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08); width: 100%; max-width: 420px; padding: 35px 28px; }
        .brand-title { color: #0284c7; font-size: 28px; font-weight: 800; }
        .form-control { background-color: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 14px 16px; }
        .btn-primary { background-color: #0284c7; border: none; border-radius: 12px; padding: 14px; font-weight: 700; }
    </style>
</head>
<body>
<div class="auth-card text-center">
    <h2 class="brand-title mb-2">Verify OTP</h2>
    <p class="text-muted mb-4">Enter OTP sent to your email and set a new password</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 rounded-3 fs-6 mb-3"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3 text-start">
            <label class="form-label fw-bold">6-Digit OTP</label>
            <input type="text" name="otp" class="form-control" placeholder="Enter OTP code" required>
        </div>
        <div class="mb-4 text-start">
            <label class="form-label fw-bold">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 shadow-sm">Reset Password</button>
    </form>
</div>
</body>
</html>
