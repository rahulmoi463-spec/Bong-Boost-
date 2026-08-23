<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ইউজার আগে থেকেই লগইন থাকলে ড্যাশবোর্ডে পাঠাবে
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bong Boost - Trusted SMM Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0b0f19;
            color: #ffffff;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 80px;
        }
        .hero-section {
            text-align: center;
            padding: 50px 20px 30px;
            max-width: 500px;
            margin: 0 auto;
        }
        
        /* Modern 3D Text Effects */
        .brand-name {
            font-size: 42px;
            font-weight: 900;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 20px rgba(79, 172, 254, 0.4), 0 2px 4px rgba(0,0,0,0.5);
            letter-spacing: -1px;
            margin-bottom: 15px;
        }
        .hero-title {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            text-shadow: 0 4px 12px rgba(0,0,0,0.6);
        }
        .hero-subtitle {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 8px 16px rgba(236, 72, 153, 0.4);
            margin-bottom: 35px;
        }
        
        /* 3D Button Style */
        .btn-start {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 19px;
            padding: 16px 28px;
            border-radius: 30px;
            width: 100%;
            display: block;
            text-decoration: none;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.5), inset 0 2px 2px rgba(255,255,255,0.3);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-start:active {
            transform: translateY(2px);
            box-shadow: 0 5px 15px -3px rgba(99, 102, 241, 0.5);
        }
        
        /* Comparison Section */
        .comparison-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .comp-card {
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .our-panel {
            background: #111827;
            border: 2px solid #22c55e;
            box-shadow: 0 10px 30px -10px rgba(34, 197, 94, 0.2);
        }
        .other-panel {
            background: #111827;
            border: 1px solid #334155;
        }
        .comp-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 20px;
        }
        .comp-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .comp-list li {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #e2e8f0; /* স্পষ্ট কালার দেওয়া হলো */
        }
        .icon-check { color: #22c55e; font-size: 20px; font-weight: bold; }
        .icon-cross { color: #ef4444; font-size: 20px; font-weight: bold; }
        
        /* Reviews Section */
        .reviews-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .review-card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .reviewer-name {
            font-weight: 700;
            font-size: 17px;
            color: #38bdf8;
            margin-bottom: 5px;
        }
        .review-text {
            font-size: 14px;
            color: #cbd5e1;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .stars {
            color: #f59e0b;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="brand-name">Bong Boost</div>
        <div class="hero-title">Trusted by 1.5 Lakh+</div>
        <div class="hero-title">Users</div>
        <div class="hero-subtitle">Indiawide!</div>

        <a href="login.php" class="btn-start">Start Now</a>
    </div>

    <!-- Comparison Section -->
    <div class="comparison-container">
        <!-- Our Panel Card -->
        <div class="comp-card our-panel">
            <div class="comp-title text-success">Our Panel</div>
            <ul class="comp-list">
                <li><span class="icon-check">✓</span> Low prices</li>
                <li><span class="icon-check">✓</span> Unlimited API</li>
                <li><span class="icon-check">✓</span> Very fast load speed</li>
                <li><span class="icon-check">✓</span> Great mobile version</li>
                <li><span class="icon-check">✓</span> Instant order delivery</li>
                <li><span class="icon-check">✓</span> 24/7 WhatsApp Support</li>
            </ul>
        </div>

        <!-- Other Panels Card -->
        <div class="comp-card other-panel">
            <div class="comp-title text-danger">Other panels</div>
            <ul class="comp-list">
                <li><span class="icon-cross">✕</span> High prices</li>
                <li><span class="icon-cross">✕</span> Limited API</li>
                <li><span class="icon-cross">✕</span> Average or slow speed</li>
                <li><span class="icon-cross">✕</span> No mobile or not adapted</li>
                <li><span class="icon-cross">✕</span> Reselling services</li>
                <li><span class="icon-cross">✕</span> Delayed support response</li>
            </ul>
        </div>
    </div>

    <!-- Customer Reviews Section -->
    <div class="reviews-container">
        <h3 class="text-center fw-bold mb-4" style="color: #ffffff;">Customer Reviews</h3>

        <div class="review-card">
            <div class="reviewer-name">Rahul Mehta</div>
            <div class="review-text">Bong Boost is simply awesome! I've used it for Instagram and YouTube, and I'm 100% satisfied with the results. Totally worth it!</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Lucas S.</div>
            <div class="review-text">I tried Bong Boost after a friend's recommendation, and it's been great so far. The orders start instantly, no fake-looking followers!</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Amit Sharma</div>
            <div class="review-text">Bhai, ye ekdum next level panel hai! Followers aur views dono fast complete hote hain. Support team bhi bahut help karti hai.</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Subhajit Roy</div>
            <div class="review-text">West Bengal er best SMM panel. Rate khub er kom ebong Order instant start hoy. Sobai try korte paro!</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Pooja Verma</div>
            <div class="review-text">Best panel for Instagram reels views. Instant delivery every time. Fully trusted!</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Vikram Singh</div>
            <div class="review-text">Very cheap prices compared to other panels. Automatic UPI payment works smoothly. 10/10 rating.</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Rohan Das</div>
            <div class="review-text">Awesome customer care support! My order got stuck once and they resolved it within 5 minutes on WhatsApp.</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Suman Mondal</div>
            <div class="review-text">Khub bhalo service. Ami regular views ar subscribers nei. Kono drop hoy na.</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Deepak Kumar</div>
            <div class="review-text">Super fast service delivery. The UI is very clean and easy to use even for new beginners.</div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>

        <div class="review-card">
            <div class="reviewer-name">Anish Banerjee</div>
            <div class="review-text">Honest pricing and reliable panel. Recommended to all social media creators in India!</div>
            <div class="stars">⭐⭐⭐⭐⭐⭐</div>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <?php include 'components/bottom-nav.php'; ?>

</body>
</html>
