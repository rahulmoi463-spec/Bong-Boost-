<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

if (!isset($_SESSION['reset_user'])) {
    header("Location: forgot-password.php");
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $username = $_SESSION['reset_user'];

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $res = pg_query_params($dbconn, "UPDATE users SET password = $1 WHERE username = $2", array($hashed_password, $username));

        if ($res) {
            unset($_SESSION['reset_user']);
            echo "<script>alert('Password reset successful! Please login with your new password.'); window.location.href='login.php';</script>";
            exit();
        } else {
            $error = "Failed to update password. Please try again.";
        }
    } else {
        $error = "Please enter a new password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - Bong Boost</title>
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
    <h2 class="brand-title mb-2">Set New Password</h2>
    <p class="text-muted mb-4">Create a new password for your account</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 rounded-3 fs-6 mb-3"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4 text-start">
            <label class="form-label fw-bold">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 shadow-sm">Save New Password</button>
    </form>
</div>
</body>
</html>
