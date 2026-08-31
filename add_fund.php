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
        $msg = "Amount must be between ₹20 and ₹10,000";
    } 
    // 2. Strict Validation: Must be 10 to 35 alphanumeric characters (No spaces or garbage text)
    elseif (!preg_match('/^[a-zA-Z0-9]{10,22}$/', $txn_input)) {
        $msg = "Please enter a valid 10-35 digit UTR or Transaction ID (No spaces/symbols allowed).";
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; color: #1e293b; font-family: 'Inter', sans-serif; }
        .card { background: #ffffff; border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .payment-tab-btn { border-radius: 8px; font-weight: 600; padding: 10px; width: 48%; }
        .qr-card { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 20px; text-align: center; background: #fafafa; }
        .qr-img { width: 220px; height: 220px; object-fit: contain; border-radius: 8px; }
        .btn-primary { background-color: #4f46e5; border: none; border-radius: 8px; font-weight: 600; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-dark m-0">Add Wallet Balance</h4>
                    <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">Dashboard</a>
                </div>

                <?php if($msg): ?><div class="alert alert-danger rounded-3"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

                <!-- Payment Method Buttons -->
                <div class="d-flex justify-content-between mb-3">
                    <button type="button" class="btn btn-primary payment-tab-btn" id="btnPhonePe" onclick="showQR('phonepe')">PhonePe QR</button>
                    <button type="button" class="btn btn-outline-success payment-tab-btn" id="btnGPay" onclick="showQR('gpay')">Google Pay QR</button>
                </div>

                <!-- QR Display Area -->
                <div class="mb-4">
                    <div class="qr-card" id="phonepeBox">
                        <h6 class="fw-bold text-primary mb-2">Scan & Pay via PhonePe</h6>
                        <img src="<?php echo $phonepe_qr_url; ?>" class="qr-img img-fluid" alt="PhonePe QR">
                    </div>

                    <div class="qr-card d-none" id="gpayBox">
                        <h6 class="fw-bold text-success mb-2">Scan & Pay via Google Pay</h6>
                        <img src="<?php echo $gpay_qr_url; ?>" class="qr-img img-fluid" alt="Google Pay QR">
                    </div>
                </div>

                <!-- Form -->
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Min ₹20 - Max ₹10,000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Transaction ID / UTR Number</label>
                        <input type="text" name="utr" class="form-control" placeholder="Enter Valid 10-22 Digit UTR/Txn ID" pattern="[a-zA-Z0-9]{10,22}" title="Must be 10-22 letters/numbers without spaces" required>
                    </div>
                    <button type="submit" name="submit_payment" class="btn btn-primary w-100 py-2.5">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showQR(type) {
        const phonepeBox = document.getElementById('phonepeBox');
        const gpayBox = document.getElementById('gpayBox');
        const btnPhonePe = document.getElementById('btnPhonePe');
        const btnGPay = document.getElementById('btnGPay');

        if(type === 'phonepe') {
            phonepeBox.classList.remove('d-none');
            gpayBox.classList.add('d-none');
            btnPhonePe.className = 'btn btn-primary payment-tab-btn';
            btnGPay.className = 'btn btn-outline-success payment-tab-btn';
        } else {
            gpayBox.classList.remove('d-none');
            phonepeBox.classList.add('d-none');
            btnGPay.className = 'btn btn-success payment-tab-btn';
            btnPhonePe.className = 'btn btn-outline-primary payment-tab-btn';
        }
    }

    Swal.fire({
        title: 'Payment Notice',
        text: 'Minimum Payment: ₹20 | Maximum Payment: ₹10,000',
        icon: 'info',
        confirmButtonColor: '#4f46e5'
    });

    <?php if($success): ?>
        Swal.fire({
            title: 'Payment Submitted Successfully',
            text: 'Balance will be added shortly after verification.',
            icon: 'success',
            confirmButtonColor: '#4f46e5'
        }).then(() => { window.location.href = 'dashboard.php'; });
    <?php endif; ?>
</script>
</body>
</html>
