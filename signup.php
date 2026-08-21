<?php
require_once 'config/db.php';

$error = ""; $success = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($mobile) && !empty($password)) {
        
        // 1. Check Username Exists
        $check_user = pg_query_params($dbconn, "SELECT id FROM users WHERE username = $1", array($username));

        // 2. Check Mobile Exists
        $check_mobile = pg_query_params($dbconn, "SELECT id FROM users WHERE mobile = $1", array($mobile));

        if ($check_user && pg_num_rows($check_user) > 0) {
            $error = "Username already exists!";
        } elseif ($check_mobile && pg_num_rows($check_mobile) > 0) {
            $error = "This mobile number is already registered! Please use another number.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $insert_query = "INSERT INTO users (username, mobile, password, balance, status) VALUES ($1, $2, $3, 0.00, 'active')";
            $result = pg_query_params($dbconn, $insert_query, array($username, $mobile, $hashed_password));
            
            if ($result) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed!";
            }
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
    <title>Sign Up - Bong Boost</title>
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
            border-color: #10b981;
            color: #0f172a;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        .btn-success {
            background-color: #10b981;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-success:hover {
            background-color: #059669;
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
        <h2 class="brand-title">Sign Up</h2>
        <p class="brand-subtitle mt-1">Create a new Bong Boost account</p>
    </div>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger py-2 text-center rounded-3 fs-6 mb-3 fw-medium"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if(!empty($success)): ?>
        <div class="alert alert-success py-2 text-center rounded-3 fs-6 mb-3 fw-medium"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Choose a username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number" value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
        </div>

        <button type="submit" name="register" class="btn btn-success w-100 mb-3 shadow-sm">Create Account</button>

        <div class="text-center mt-3 fs-6">
            <span class="text-secondary">Already have an account?</span> 
            <a href="login.php" class="auth-link ms-1">Login</a>
        </div>
    </form>
</div>

</body>
</html>
