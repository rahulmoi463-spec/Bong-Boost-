<?php session_start(); ?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { width: 90%; max-width: 380px; padding: 25px; background: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; text-align: center; color: #333; }
        input, button { width: 100%; padding: 12px; margin-top: 12px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; }
        button { background: #28a745; color: white; border: none; font-weight: bold; cursor: pointer; }
        .msg { color: #d9534f; background: #fdf7f7; padding: 8px; border-radius: 5px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Forgot Password</h2>
        <?php 
        if(isset($_SESSION['error'])){ echo "<div class='msg'>".$_SESSION['error']."</div>"; unset($_SESSION['error']); }
        ?>
        <form action="send-otp.php" method="POST">
            <label>আপনার অ্যাকাউন্ট ইমেইলটি দিন:</label>
            <input type="email" name="email" required placeholder="example@gmail.com">
            <button type="submit">Send OTP</button>
        </form>
    </div>
</body>
</html>
