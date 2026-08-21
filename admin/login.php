<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin@96#') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Admin Credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> 
    body { background-color: #f8f9fa; color: #212529; } 
    .card { background-color: #ffffff; border: 1px solid #dee2e6; color: #212529; box-shadow: 0 4px 12px rgba(0,0,0,0.1); } 
    .form-control { background-color: #ffffff !important; color: #212529 !important; border: 1px solid #ced4da !important; }
    .form-control::placeholder { color: #6c757d !important; opacity: 1; }
    label { color: #212529 !important; font-weight: 600; }
    </style>
</head>
<body class="d-flex align-items-center vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow-lg border-danger">
                <h4 class="text-center text-danger mb-3">Admin Panel</h4>
                <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Admin Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Enter admin username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter password">
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Access Panel</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
