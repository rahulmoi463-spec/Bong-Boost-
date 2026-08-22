<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Bong Boost</title>
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
    <h2 class="brand-title mb-2">Forgot Password</h2>
    <p class="text-muted mb-4">Enter your registered email to receive OTP</p>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger py-2 rounded-3 fs-6 mb-3"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="send-otp.php" method="POST">
        <div class="mb-4 text-start">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 shadow-sm">Send OTP</button>
        <div class="mt-3">
            <a href="login.php" class="text-decoration-none fw-bold" style="color: #0284c7;">Back to Login</a>
        </div>
    </form>
</div>
</body>
</html>
