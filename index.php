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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            color: #ffffff;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 80px;
        }
        .hero-section {
            text-align: center;
            padding: 40px 20px 20px;
            max-width: 500px;
            margin: 0 auto;
        }
        .brand-name {
            font-size: 38px;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: -1px;
            margin-bottom: 15px;
        }
        .hero-title {
            font-size: 30px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
        }
        .hero-subtitle {
            font-size: 30px;
            font-weight: 800;
            color: #818cf8;
            margin-bottom: 30px;
        }
        .btn-start {
            background: #6366f1;
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            padding: 14px 28px;
            border-radius: 30px;
            width: 100%;
            display: block;
            text-decoration: none;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            margin-bottom: 12px;
        }
        .btn-app {
            background: #1e293b;
            color: #ffffff;
            font-weight: 700;
            font-size: 17px;
            padding: 14px 28px;
            border-radius: 30px;
            width: 100%;
            display: block;
            text-decoration: none;
            border: 1px solid #334155;
            margin-bottom: 30px;
        }
        
        /* Comparison Section */
        .comparison-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .comp-card {
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .our-panel {
            background: #1e293b;
            border: 2px solid #22c55e;
        }
        .other-panel {
            background: #1e293b;
            border: 1px solid #334155;
            opacity: 0.85;
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
            background: #1e293b;
            border: 1px solid #334155;
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
        <a href="login.php" class="btn-app">Download App</a>
    </div>

    <!-- Comparison Section -->
    <div class="comparison-container">
        <!-- Our Panel Card -->
        <div class="comp-card our-panel shadow-lg">
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
            <ul class="comp-list text-muted">
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
            <div class="stars">⭐⭐⭐⭐⭐</div>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <?php include 'components/bottom-nav.php'; ?>

</body>
</html>
