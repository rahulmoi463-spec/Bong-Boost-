<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $res = pg_query_params($dbconn, "SELECT * FROM users WHERE username = $1", array($username));

        if ($res && pg_num_rows($res) == 1) {
            $user = pg_fetch_assoc($res);
            if (password_verify($password, $user['password'])) {
                if (isset($user['status']) && $user['status'] == 'banned') {
                    $error = "Your account is banned!";
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                $error = "Invalid Password!";
            }
        } else {
            $error = "User not found!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0f2fe 0%, #f1f5f9 100%);
            color: #0f172a;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
        }
        .auth-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
            width: 100%;
            max-width: 420px;
            padding: 35px 28px;
        }
        .brand-title {
            color: #0284c7;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            color: #64748b;
            font-size: 15px;
            font-weight: 500;
        }
        .form-label {
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .form-control {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            color: #0f172a;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 16px;
            font-weight: 500;
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: #0284c7;
            color: #0f172a;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
        }
        .btn-primary {
            background-color: #0284c7;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #0369a1;
        }
        .auth-link {
            color: #0284c7;
            text-decoration: none;
            font-weight: 700;
        }
        .auth-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="text-center mb-4">
        <h2 class="brand-title">Bong Boost</h2>
        <p class="brand-subtitle mt-1">Welcome back! Please enter details</p>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger py-2.5 text-center rounded-3 fs-6 mb-3 fw-medium"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <div class="text-end mb-4">
            <a href="forgot-password.php" class="auth-link" style="font-size: 14px;">Forgot Password?</a>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100 mb-3 shadow-sm">Sign In</button>

        <div class="text-center mt-3 fs-6">
            <span class="text-secondary">Don't have an account?</span> 
            <a href="signup.php" class="auth-link ms-1">Sign Up</a>
        </div>
    </form>
</div>

</body>
</html>
