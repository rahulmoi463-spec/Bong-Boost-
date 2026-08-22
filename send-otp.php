<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $_SESSION['error'] = "ইমেইল অ্যাড্রেস প্রদান করুন।";
        header("Location: forgot-password.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = "এই ইমেইলে কোনো অ্যাকাউন্ট পাওয়া যায়নি।";
        header("Location: forgot-password.php");
        exit();
    }

    $otp = rand(100000, 999999);
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $stmt = $pdo->prepare("INSERT INTO password_resets (email, otp, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$email, $otp, $expires_at]);

    // আপনার Resend API Key
    $resend_api_key = 're_EBVoC4MA_3pdP3...'; // এখানে কপি করা পুরো API Key-টি বসাবেন

    $data = [
        'from' => 'onboarding@resend.dev',
        'to' => [$email],
        'subject' => 'Password Reset OTP Code',
        'html' => "<p>আপনার পাসওয়ার্ড রিসেট করার OTP কোড হলো: <strong>{$otp}</strong>। এটি ১০ মিনিট কার্যকর থাকবে।</p>"
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
    $_SESSION['success'] = "আপনার ইমেইলে OTP পাঠানো হয়েছে।";
    header("Location: verify-otp.php");
    exit();
}
