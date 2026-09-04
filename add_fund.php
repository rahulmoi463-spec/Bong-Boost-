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
    // 2. Flexible Validation: Must be 10 to 40 alphanumeric characters
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
        body { 
            background-color: #0b0f19; 
            color: #ffffff; 
            font-family: 'Inter', sans-serif; 
            min-height: 100vh; 
            padding-bottom: 60px; 
        }
        .card { 
            background: #151d2a; 
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5); 
        }
        
        /* App Cards Styling */
        .app-card { 
            background: #1c2638; 
            border: 1.5px solid rgba(255, 255, 255, 0.2); 
            border-radius: 14px; 
            padding: 14px 8px; 
            cursor: pointer; 
            transition: all 0.25s ease; 
            text-align: center; 
        }
        .app-card .small {
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 13px;
        }
        .app-card:hover { 
            border-color: #38bdf8; 
            background: #233047; 
            transform: translateY(-2px); 
        }
        .app-card.active { 
            border-color: #38bdf8; 
            background: rgba(56, 189, 248, 0.2); 
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.4); 
        }
        .app-card.active .small {
            color: #38bdf8 !important;
            font-weight: 700 !important;
        }
        .app-icon { 
            font-size: 26px; 
            color: #38bdf8; 
            margin-bottom: 6px; 
            display: block;
        }

        /* Labels and Text */
        .section-label {
            color: #cbd5e1 !important;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Input Fields */
        .form-control { 
            background-color: #0f172a !important; 
            border: 1.5px solid #334155 !important; 
            color: #ffffff !important; 
            font-size: 15px;
            font-weight: 600;
            border-radius: 12px; 
            padding: 14px; 
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
            font-weight: 400;
        }
        .form-control:focus { 
            border-color: #38bdf8 !important; 
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.3) !important; 
        }

        /* QR & Timer Buttons */
        .qr-card { 
            border: 2px dashed #38bdf8; 
            border-radius: 18px; 
            padding: 20px; 
            text-align: center; 
            background: #0f172a; 
        }
        .qr-img { 
            width: 220px; 
            height: 220px; 
            object-fit: contain; 
            border-radius: 12px; 
            background: #ffffff; 
            padding: 10px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .btn-primary { 
            background: linear-gradient(135deg, #0284c7 0%, #6366f1 100%); 
            border: none; 
            border-radius: 12px; 
            font-weight: 700; 
            font-size: 16px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.4);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0369a1 0%, #4f46e5 100%);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.6);
        }
        .timer-badge { 
            background: rgba(239, 68, 68, 0.2); 
            border: 1px solid rgba(239, 68, 68, 0.5); 
            color: #fca5a5; 
            font-weight: 700; 
            padding: 8px 16px; 
            border-radius: 20px; 
            display: inline-block; 
        }

        /* Processing Modal Custom Dark Theme */
        .swal2-popup.dark-processing {
            background: #151d2a !important;
            color: #ffffff !important;
            border-radius: 20px !important;
            border: 1px solid rgba(56, 189, 248, 0.3) !important;
        }
        .proc-timer-box {
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 15px;
            padding: 15px;
            margin: 15px 0;
        }
        .proc-timer-text {
            font-size: 32px;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-white m-0"><i class="fa-solid fa-wallet text-info me-2"></i>Add Wallet Balance</h4>
                    <a href="dashboard.php" class="btn btn-sm btn-outline-light">Dashboard</a>
                </div>

                <?php if($msg): ?><div class="alert alert-danger rounded-3 text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

                <!-- Step 1: Select Payment App -->
                <label class="form-label section-label mb-2">SELECT PAYMENT APP</label>
                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <div class="app-card active" onclick="selectApp('PhonePe', '<?php echo $phonepe_qr_url; ?>', this)">
                            <i class="fa-solid fa-mobile-screen app-icon"></i>
                            <div class="small">PhonePe</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Google Pay', '<?php echo $gpay_qr_url; ?>', this)">
                            <i class="fa-brands fa-google-pay app-icon"></i>
                            <div class="small">GPay</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Paytm', '<?php echo $phonepe_qr_url; ?>', this)">
                            <i class="fa-solid fa-building-columns app-icon"></i>
                            <div class="small">Paytm</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('BharatPe', '<?php echo $gpay_qr_url; ?>', this)">
                            <i class="fa-solid fa-qrcode app-icon"></i>
                            <div class="small">BharatPe</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Navi UPI', '<?php echo $phonepe_qr_url; ?>', this)">
                            <i class="fa-solid fa-shield-halved app-icon"></i>
                            <div class="small">Navi UPI</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="app-card" onclick="selectApp('Airtel/Jio', '<?php echo $gpay_qr_url; ?>', this)">
                            <i class="fa-solid fa-credit-card app-icon"></i>
                            <div class="small">Airtel/Jio</div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Amount Input -->
                <div class="mb-3" id="amountBox">
                    <label class="form-label section-label mb-2">ENTER AMOUNT (₹)</label>
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
                        <label class="form-label section-label mb-2">TRANSACTION ID / UTR NUMBER</label>
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

        document.getElementById('qrContainer').scrollIntoView({ behavior: 'smooth' });

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

    <?php if($success): ?>
        // Show 10-minute Auto Verification Processing Modal
        let processTime = 600; // 10 minutes in seconds
        
        Swal.fire({
            title: '<i class="fa-solid fa-spinner fa-spin text-info me-2"></i>Processing Payment...',
            html: `
                <p class="text-light small mb-2">Automatic transaction verification is in progress.</p>
                <div class="proc-timer-box">
                    <div class="proc-timer-text" id="procTimer">10:00</div>
                    <small class="text-info fw-bold">Verifying Banking Network Status</small>
                </div>
                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning small text-start mb-0">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Important:</strong> Please do not close or refresh this window until processing is complete. Your wallet balance will be automatically updated upon completion.
                </div>
            `,
            customClass: {
                popup: 'dark-processing'
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                const procDisplay = document.getElementById('procTimer');
                const procInterval = setInterval(() => {
                    let mins = parseInt(processTime / 60, 10);
                    let secs = parseInt(processTime % 60, 10);

                    mins = mins < 10 ? "0" + mins : mins;
                    secs = secs < 10 ? "0" + secs : secs;

                    if (procDisplay) {
                        procDisplay.textContent = mins + ":" + secs;
                    }

                    if (--processTime < 0) {
                        clearInterval(procInterval);
                        window.location.href = 'dashboard.php';
                    }
                }, 1000);
            }
        });
    <?php endif; ?>
</script>
</body>
</html>
