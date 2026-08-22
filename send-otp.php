<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $_SESSION['error'] = "Please enter your email address.";
        header("Location: forgot-password.php");
        exit();
    }

    $res = pg_query_params($dbconn, "SELECT * FROM users WHERE email = $1", array($email));

    if (!$res || pg_num_rows($res) == 0) {
        $_SESSION['error'] = "No account found with this email.";
        header("Location: forgot-password.php");
        exit();
    }

    $otp = rand(100000, 999999);
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    pg_query_params($dbconn, "INSERT INTO password_resets (email, otp, expires_at) VALUES ($1, $2, $3)", array($email, $otp, $expires_at));

    $resend_api_key = 're_EBVoC4MA_3pdP3...';

    $data = [
        'from' => 'onboarding@resend.dev',
        'to' => [$email],
        'subject' => 'Password Reset OTP Code - Bong Boost',
        'html' => "<div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'><h2 style='color: #0284c7;'>Bong Boost</h2><p>Your password reset OTP code is: <b style='font-size: 20px; color: #0284c7;'>{$otp}</b></p><p>This code will expire in 10 minutes.</p></div>"
    ];

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $resend_api_key,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $_SESSION['reset_email'] = $email;
    header("Location: verify-otp.php");
    exit();
}
