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

$msg = ""; $success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_payment'])) {
    $amount = (float)$_POST['amount'];
    $txn_input = isset($_POST['utr']) ? trim($_POST['utr']) : '';

    // 1. Check Amount Limit
    if ($amount < 20 || $amount > 10000) {
        $msg = "Amount must be between ₹20 and ₹10,000.";
    } 
    // 2. Flexible Validation: Must be 10 to 40 alphanumeric characters (Support long Txn IDs)
    elseif (!preg_match('/^[a-zA-Z0-9]{10,40}$/', $txn_input)) {
        $msg = "Please enter a valid 10 to 40 digit UTR or Transaction ID (No spaces/symbols allowed).";
    } 
    else {
        // 3. Duplicate UTR Check in Database using PostgreSQL
        $check_duplicate = pg_query_params($dbconn, "SELECT id FROM payments WHERE txn_id = $1", array($txn_input));
        
        if ($check_duplicate && pg_num_rows($check_duplicate) > 0) {
            $msg = "This Transaction ID / UTR has already been submitted! Please check your payment status.";
        } else {
            // Save Valid and Unique Payment
            $insert_res = pg_query_params($dbconn, 
                "INSERT INTO payments (user_id, amount, txn_id, status) VALUES ($1, $2, $3, 'Pending')", 
                array($user_id, $amount, $txn_input)
            );

            if ($insert_res) {
                $success = true;
            } else {
                $msg = "Payment submission failed! Please try again.";
            }
        }
    }
}

// UPI IDs hidden in backend
$phonepe_upi = "7029379899-2@axl";
$gpay_upi = "moiuttam50-1@okaxis";

$phonepe_qr_data = urlencode("upi://pay?pa=" . $phonepe_upi . "&pn=BongBoost&cu=INR");
$gpay_qr_data = urlencode("upi://pay?pa=" . $gpay_upi . "&pn=UttamMoi&cu=INR");

$phonepe_qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . $phonepe_qr_data;
$gpay_qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . $gpay_qr_data;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Fund - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; min-height: 100vh; padding-bottom: 60px; }
        .card { background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .app-card { background: #0f172a; border: 1.5px solid #334155; border-radius: 12px; padding: 12px; cursor: pointer; transition: all 0.2s ease; text-align: center; }
        .app-card:hover, .app-card.active { border-color: #38bdf8; background: rgba(56, 189, 248, 0.1); transform: translateY(-2px); }
        .app-icon { font-size: 22px; color: #38bdf8; margin-bottom: 4px; }
        .qr-card { border: 2px dashed #38bdf8; border-radius: 16px; padding: 20px; text-align: center; background: #0f172a; }
        .qr-img { width: 210px; height: 210px; object-fit: contain; border-radius: 10px; background: #fff; padding: 8px; }
        .btn-primary { background: linear-gradient(135deg, #38bdf8 0%, #8b5cf6 100%); border: none; border-radius: 10px; font-weight: 600; }
        .timer-badge { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; font-weight: 700; padding: 6px 14px; border-radius: 20px; display: inline-block; }
        .form-control { background-color: #0f172a !important; border: 1.5px solid #334155 !important; color: #f8fafc !important; border-radius: 10px; padding: 12px; }
        .form-control:focus { border-color: #38bdf8 !important; box-shadow: 0 0 10px rgba(56, 189, 248, 0.2); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-white m-0"><i class="fa-solid fa-wallet text-info me-2"></i>Add Wallet Balance</h4>
                    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Dashboard</a>
                </div>

                <?php if($msg): ?><div class="alert alert-danger rounded-3 text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

                <!-- Step 1: Select Payment App -->
                <label class="form-label text-secondary small fw-bold mb-2">SELECT PAYMENT APP</label>
                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <div class="app-card active" onclick="selectApp('PhonePe', '<?php echo $phonepe_qr_url; ?>', this)">
                            <i class="fa-solid fa-mobile-screen app-icon"></i>
                            <div class="small fw-bold">PhonePe</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Google Pay', '<?php echo $gpay_qr_url; ?>', this)">
                            <i class="fa-brands fa-google-pay app-icon"></i>
                            <div class="small fw-bold">GPay</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Paytm', '<?php echo $phonepe_qr_url; ?>', this)">
                            <i class="fa-solid fa-building-columns app-icon"></i>
                            <div class="small fw-bold">Paytm</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('BharatPe', '<?php echo $gpay_qr_url; ?>', this)">
                            <i class="fa-solid fa-qrcode app-icon"></i>
                            <div class="small fw-bold">BharatPe</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Navi UPI', '<?php echo $phonepe_qr_url; ?>', this)">
                            <i class="fa-solid fa-shield-halved app-icon"></i>
                            <div class="small fw-bold">Navi UPI</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Airtel/Jio', '<?php echo $gpay_qr_url; ?>', this)">
                            <i class="fa-solid fa-credit-card app-icon"></i>
                            <div class="small fw-bold">Airtel/Jio</div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Amount Input -->
                <div class="mb-3" id="amountBox">
                    <label class="form-label text-secondary small fw-bold">ENTER AMOUNT (₹)</label>
                    <input type="number" step="0.01" id="inputAmount" class="form-control" placeholder="Min ₹20 - Max ₹10,000">
                    <button type="button" onclick="generatePayment()" class="btn btn-primary w-100 py-2.5 mt-3">
                        <i class="fa-solid fa-bolt me-2"></i>Proceed to Pay
                    </button>
                </div>

                <!-- Step 3: QR Code & Countdown Timer Display -->
                <div class="qr-card d-none mb-4" id="qrContainer">
                    <div class="mb-2">
                        <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 px-3 py-1.5 rounded-pill" id="selectedAppName">
                            Scan & Pay
                        </span>
                    </div>

                    <img id="qrImage" src="" class="qr-img img-fluid my-2" alt="Payment QR Code">

                    <div class="mt-2 mb-3">
                        <div class="timer-badge">
                            <i class="fa-regular fa-clock me-1"></i>Session Expires In: <span id="timer">03:00</span>
                        </div>
                    </div>

                    <p class="text-secondary small m-0">Scan the QR code using your payment app and submit the UTR / Transaction ID below.</p>
                </div>

                <!-- Step 4: Final Form Submission -->
                <form method="POST" id="paymentForm" class="d-none">
                    <input type="hidden" name="amount" id="finalAmount">

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">TRANSACTION ID / UTR NUMBER</label>
                        <input type="text" name="utr" class="form-control" placeholder="Enter 10 to 40 Digit UTR or Txn ID" minlength="10" maxlength="40" pattern="[a-zA-Z0-9]{10,40}" title="10 to 40 letters/numbers without spaces" required>
                    </div>

                    <button type="submit" name="submit_payment" class="btn btn-primary w-100 py-2.5">
                        <i class="fa-solid fa-paper-plane me-2"></i>Submit Payment
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let activeQrUrl = '<?php echo $phonepe_qr_url; ?>';
    let selectedApp = 'PhonePe';
    let countdownTimer;

    function selectApp(appName, qrUrl, el) {
        selectedApp = appName;
        activeQrUrl = qrUrl;

        document.querySelectorAll('.app-card').forEach(card => card.classList.remove('active'));
        el.classList.add('active');
    }

    function generatePayment() {
        const amtInput = document.getElementById('inputAmount').value;
        const amount = parseFloat(amtInput);

        if (isNaN(amount) || amount < 20 || amount > 10000) {
            Swal.fire({
                title: 'Invalid Amount',
                text: 'Please enter an amount between ₹20 and ₹10,000',
                icon: 'warning',
                confirmButtonColor: '#38bdf8'
            });
            return;
        }

        document.getElementById('finalAmount').value = amount;
        document.getElementById('qrImage').src = activeQrUrl;
        document.getElementById('selectedAppName').innerText = 'Pay via ' + selectedApp + ' - ₹' + amount;

        document.getElementById('qrContainer').classList.remove('d-none');
        document.getElementById('paymentForm').classList.remove('d-none');

        // Smooth scroll to QR section
        document.getElementById('qrContainer').scrollIntoView({ behavior: 'smooth' });

        // Start 3 Minutes Countdown
        startTimer(180);
    }

    function startTimer(duration) {
        clearInterval(countdownTimer);
        let timer = duration, minutes, seconds;
        const display = document.getElementById('timer');

        countdownTimer = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(countdownTimer);
                Swal.fire({
                    title: 'Session Expired',
                    text: 'Payment session expired. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#38bdf8'
                }).then(() => {
                    location.reload();
                });
            }
        }, 1000);
    }

    Swal.fire({
        title: 'Payment Notice',
        text: 'Minimum Payment: ₹20 | Maximum Payment: ₹10,000',
        icon: 'info',
        confirmButtonColor: '#38bdf8'
    });

    <?php if($success): ?>
        Swal.fire({
            title: 'Payment Submitted Successfully',
            text: 'Your balance will be verified and credited shortly by Admin.',
            icon: 'success',
            confirmButtonColor: '#38bdf8'
        }).then(() => { window.location.href = 'dashboard.php'; });
    <?php endif; ?>
</script>
</body>
</html>
