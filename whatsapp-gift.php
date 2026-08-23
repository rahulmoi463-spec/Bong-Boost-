<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// আপনার হোয়াটসঅ্যাপ নম্বর (কান্ট্রি কোড ৯১ সহ)
$admin_whatsapp = "917718231993"; // <--- এখানে আপনার আসল WhatsApp Number বসান

// ডাইরেক্ট মেসেজ টেক্সট
$default_message = urlencode("Hello Bong Boost, I want to claim my FREE 1000 Instagram Views bonus!");
$whatsapp_url = "https://wa.me/{$admin_whatsapp}?text={$default_message}";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Free 1000 Views - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0b0f19;
            color: #ffffff;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 90px;
        }
        .gift-container {
            max-width: 450px;
            margin: 40px auto 20px;
            padding: 0 20px;
            text-align: center;
        }
        .gift-card {
            background: #111827;
            border: 2px solid #25d366;
            border-radius: 24px;
            padding: 30px 20px;
            box-shadow: 0 10px 30px -10px rgba(37, 211, 102, 0.3);
        }
        .gift-icon {
            font-size: 60px;
            margin-bottom: 15px;
            display: inline-block;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-15px);}
            60% {transform: translateY(-7px);}
        }
        .gift-title {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #25d366 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        .gift-subtitle {
            font-size: 15px;
            color: #cbd5e1;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .steps-box {
            background: #1f2937;
            border-radius: 16px;
            padding: 15px;
            text-align: left;
            margin-bottom: 25px;
        }
        .step-item {
            font-size: 14px;
            color: #e2e8f0;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .step-item:last-child {
            margin-bottom: 0;
        }
        .step-num {
            background: #25d366;
            color: #000;
            font-weight: 800;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        .btn-whatsapp {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 18px;
            padding: 16px 20px;
            border-radius: 30px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            box-shadow: 0 10px 25px -5px rgba(37, 211, 102, 0.4);
            border: none;
            transition: transform 0.2s ease;
        }
        .btn-whatsapp:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

    <div class="gift-container">
        <div class="gift-card">
            <div class="gift-icon">🎁</div>
            <div class="gift-title">Claim 1000 Free Views!</div>
            <p class="gift-subtitle">Get 1000 Instagram Reel Views completely FREE directly on your WhatsApp!</p>

            <div class="steps-box">
                <div class="step-item">
                    <span class="step-num">1</span> Click the WhatsApp button below
                </div>
                <div class="step-item">
                    <span class="step-num">2</span> Send the auto-generated message
                </div>
                <div class="step-item">
                    <span class="step-num">3</span> Send your Instagram Reel Link to get views
                </div>
            </div>

            <a href="<?php echo $whatsapp_url; ?>" target="_blank" class="btn-whatsapp">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                Claim Free Bonus Now
            </a>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <?php include 'components/bottom-nav.php'; ?>

</body>
</html>
