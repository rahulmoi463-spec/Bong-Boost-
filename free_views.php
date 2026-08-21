<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// Fetch user data using PostgreSQL
$user_res = pg_query_params($dbconn, "SELECT * FROM users WHERE id = $1", array($user_id));
$user = pg_fetch_assoc($user_res);

if ($user['status'] == 'banned') { 
    session_destroy(); 
    die("Account Banned."); 
}

// Get Real IP Address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { return $_SERVER['HTTP_CLIENT_IP']; }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]; }
    return $_SERVER['REMOTE_ADDR'];
}

$user_ip = getUserIP();

// Fraud Check 1: Check Database by User ID & IP Address
$check_stmt = pg_query_params($dbconn, "SELECT last_claimed_at FROM free_claims 
                            WHERE user_id = $1 OR ip_address = $2 
                            ORDER BY id DESC LIMIT 1", array($user_id, $user_ip));

$last_claim = pg_fetch_assoc($check_stmt);

$can_claim = true;
$time_remaining = 0;

if ($last_claim) {
    $last_time = strtotime($last_claim['last_claimed_at']);
    $diff = time() - $last_time;
    if ($diff < 86400) {
        $can_claim = false;
        $time_remaining = 86400 - $diff;
    }
}

// Fraud Check 2: Browser Cookie Check
if (isset($_COOKIE['last_free_views_claim'])) {
    $cookie_diff = time() - (int)$_COOKIE['last_free_views_claim'];
    if ($cookie_diff < 86400) {
        $can_claim = false;
        $rem = 86400 - $cookie_diff;
        if ($rem > $time_remaining) {
            $time_remaining = $rem;
        }
    }
}

$msg_success = "";
$msg_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['claim_free_views'])) {
    if (!$can_claim) {
        $msg_error = "You have already claimed free views within the last 24 hours!";
    } else {
        $reel_link = filter_var(trim($_POST['reel_link'] ?? ''), FILTER_VALIDATE_URL);
        if (!$reel_link || (strpos($reel_link, 'instagram.com') === false)) {
            $msg_error = "Please enter a valid Instagram Video or Reel URL!";
        } else {
            $now = date("Y-m-d H:i:s");
            
            // Record Claim in DB
            $inserted = pg_query_params($dbconn, "INSERT INTO free_claims (user_id, ip_address, link, last_claimed_at) 
                          VALUES ($1, $2, $3, $4)", array($user_id, $user_ip, $reel_link, $now));
            
            if ($inserted) {
                // Set Browser Cookie for 24 Hours
                setcookie('last_free_views_claim', time(), time() + 86400, "/");

                $msg_success = "Success! Your request for 500+ Free Views has been submitted successfully.";
                $can_claim = false;
                $time_remaining = 86400;
            } else {
                $msg_error = "Failed to submit claim request! Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Free Instagram Views - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background: #0f172a;
            background-image: 
                radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.25) 0px, transparent 50%),
                radial-gradient(at 90% 20%, rgba(217, 70, 239, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(14, 165, 233, 0.25) 0px, transparent 50%);
            background-attachment: fixed;
            color: #1e293b; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }
        .navbar { 
            background: rgba(15, 23, 42, 0.85) !important; 
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }
        .badge-balance { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); font-size: 14px; padding: 8px 14px; border-radius: 10px; font-weight: 700; }
        .timer-box { 
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); 
            color: #fff; 
            border-radius: 16px; 
            padding: 20px; 
            font-size: 32px; 
            font-weight: 800; 
            letter-spacing: 2px;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }
        .btn-claim { 
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); 
            color: #fff; 
            font-weight: 700; 
            border: none; 
            border-radius: 12px; 
            padding: 14px; 
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }
        .btn-claim:hover { color: #fff; opacity: 0.95; }
        .form-control {
            background-color: #f8fafc !important;
            border: 2px solid #cbd5e1 !important;
            color: #0f172a !important;
            font-weight: 600 !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold text-white fs-3" href="dashboard.php">Bong Boost</a>
    <div class="d-flex align-items-center flex-wrap gap-2">
      <span class="text-light me-2"><i class="fa-solid fa-user me-1 text-primary"></i> <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
      <span class="badge-balance me-2"><i class="fa-solid fa-wallet me-1"></i> Balance: ₹<?php echo number_format($user['balance'], 2); ?></span>
      <a href="dashboard.php" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
      <a href="free_views.php" class="btn btn-sm btn-warning fw-bold"><i class="fa-solid fa-gift me-1"></i> Free Views</a>
      <a href="logout.php" class="btn btn-sm btn-danger"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="glass-card p-4 p-md-5 text-center">
                <div class="mb-3">
                    <span class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger fs-1">
                        <i class="fa-brands fa-instagram"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-2 text-dark">Daily Free 500+ Views</h3>
                <p class="text-muted small mb-4">Enter your Instagram Reel or Video URL to claim 500 free views once every 24 hours!</p>

                <?php if($msg_error): ?><div class="alert alert-danger fw-bold rounded-3 mb-4"><?php echo $msg_error; ?></div><?php endif; ?>
                <?php if($msg_success): ?><div class="alert alert-success fw-bold rounded-3 mb-4"><?php echo $msg_success; ?></div><?php endif; ?>

                <?php if ($can_claim): ?>
                    <form method="POST">
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-dark">Instagram Reel / Video Link</label>
                            <input type="url" name="reel_link" class="form-control" placeholder="https://www.instagram.com/reel/..." required>
                        </div>
                        <button type="submit" name="claim_free_views" class="btn btn-claim w-100 fs-6 py-3"><i class="fa-solid fa-gift me-2"></i>Claim Free Views Now</button>
                    </form>
                <?php else: ?>
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 fs-6">Next Claim Available In</span>
                        <div class="timer-box shadow-sm" id="countdownTimer">00h 00m 00s</div>
                    </div>
                    <p class="text-muted small mt-3">You can claim again after the 24-hour countdown finishes!</p>
                <?php endif; ?>

                <div class="mt-4 pt-3 border-top">
                    <a href="dashboard.php" class="btn btn-sm btn-outline-secondary rounded-3"><i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    <?php if (!$can_claim): ?>
    let timeLeft = <?php echo $time_remaining; ?>;
    const timerElem = document.getElementById('countdownTimer');

    const countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            location.reload();
        } else {
            let hours = Math.floor(timeLeft / 3600);
            let minutes = Math.floor((timeLeft % 3600) / 60);
            let seconds = timeLeft % 60;

            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            timerElem.textContent = hours + "h " + minutes + "m " + seconds + "s";
            timeLeft--;
        }
    }, 1000);
    <?php endif; ?>
</script>
</body>
</html>
