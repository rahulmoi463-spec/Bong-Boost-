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
            color: #e2e8f0;
        }
        .icon-check { color: #22c55e; font-size: 20px; font-weight: bold; }
        .icon-cross { color: #ef4444; font-size: 20px; font-weight: bold; }
        
        /* Interactive Reviews Section Styles */
        .reviews-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .rating-summary {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .big-rating {
            font-size: 40px;
            font-weight: 900;
            color: #f59e0b;
        }
        .verified-badge {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 700;
            display: inline-block;
            margin-left: 6px;
        }
        .review-card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .customer-name {
            font-weight: 700;
            font-size: 16px;
            color: #38bdf8;
            margin-bottom: 3px;
        }
        .review-text {
            font-size: 14px;
            color: #cbd5e1;
            margin: 8px 0;
            line-height: 1.5;
        }
        .rating-stars {
            color: #f59e0b;
            font-size: 15px;
        }
        .helpful-btn {
            background: #1f2937;
            color: #94a3b8;
            border: none;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .helpful-btn:hover {
            background: #374151;
            color: #fff;
        }
        .filter-select {
            background: #111827;
            color: #fff;
            border: 1px solid #374151;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px;
            width: 100%;
            margin-bottom: 15px;
        }
        .admin-reply {
            background: #1e293b;
            border-left: 3px solid #38bdf8;
            padding: 8px 12px;
            margin-top: 10px;
            border-radius: 6px;
            font-size: 13px;
            color: #94a3b8;
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

    <!-- Main Interactive Customer Reviews Section -->
    <div class="reviews-container">
        <h3 class="text-center fw-bold mb-3" style="color: #ffffff;">Customer Reviews</h3>

        <!-- Rating Summary Bar -->
        <div class="rating-summary text-center">
            <div class="big-rating">4.6 ★</div>
            <div class="text-muted small">Based on 1,480+ authentic reviews</div>
            <div class="mt-2 text-warning small">
                ★★★★★ (80%) | ★★★★☆ (15%) | ★★★☆☆ (5%)
            </div>
        </div>

        <!-- Filter Dropdown -->
        <select class="filter-select" id="reviewFilter" onchange="filterReviews()">
            <option value="all">All Ratings (10 Reviews)</option>
            <option value="5">5 Star Only</option>
            <option value="4">4 Star Only</option>
            <option value="critical">3 & 2 Star (Critical Reviews)</option>
        </select>

        <!-- Reviews List (Top 10 Selected Mix Reviews) -->
        <div id="reviewList">

            <!-- Review 1 -->
            <div class="review-card" data-rating="5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Pooja Sharma <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">5 ★</span>
                </div>
                <div class="review-text">Bankura, WB — বাঁকুড়া সদর থেকে বলছি, ইনস্টাগ্রামের রিলস ভিউস ২ মিনিটে এসে গেছে! খুব ফাস্ট কাজ, থ্যাঙ্ক ইউ Bong Boost!</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
            </div>

            <!-- Review 2 -->
            <div class="review-card" data-rating="4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Rohan Sen <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">4 ★</span>
                </div>
                <div class="review-text">Kolkata, WB — সার্ভিস অনেক ফাস্ট, তবে কাল সন্ধ্যায় কিউআর পেমেন্টে ফান্ড অ্যাড হতে ৫ মিনিট সময় লেগেছিল। বাকি সব পারফেক্ট।</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
            </div>

            <!-- Review 3 -->
            <div class="review-card" data-rating="3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Amitabh Hazra <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">3 ★</span>
                </div>
                <div class="review-text">Purulia, WB — পুরুলিয়া থেকে বলছি, ফেসবুক ওয়াচটাইম প্যাক কমপ্লিট হতে ৩ দিন লেগেছিল। কাজ হয় কিন্তু স্পিড আরেকটু বাড়ানো উচিত।</div>
                <div class="admin-reply"><strong>Admin Response:</strong> Hi Amitabh, WatchTime orders take up to 48-72h due to high server load. Thanks for your patience!</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
            </div>

            <!-- Review 4 -->
            <div class="review-card" data-rating="5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Aarti Gupta <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">5 ★</span>
                </div>
                <div class="review-text">Delhi — Very low rates for Instagram likes! My online boutique post got very good engagement. Best SMM panel!</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
            </div>

            <!-- Review 5 -->
            <div class="review-card" data-rating="4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Aisha Khatun <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">4 ★</span>
                </div>
                <div class="review-text">Lucknow, UP — Telegram members service me kuch drop hua tha, par refill button dabate hi instant wapas aa gaye.</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
            </div>

            <!-- Review 6 -->
            <div class="review-card" data-rating="5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Vikram Yadav <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">5 ★</span>
                </div>
                <div class="review-text">Patna, Bihar — UPI Auto add fund perfectly kaam karta hai. Panel ka interface bhi bohot simple hai.</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
            </div>

            <!-- Review 7 -->
            <div class="review-card" data-rating="5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Moumita Chakraborty <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">5 ★</span>
                </div>
                <div class="review-text">Siliguri, WB — উত্তরবঙ্গ থেকে বলছি, পেজের লাইক প্রমোশন করিয়েছি, রেজাল্ট দেখে খুব খুশি।</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">25</span>)</button>
            </div>

            <!-- Review 8 -->
            <div class="review-card" data-rating="5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Taniya Mondal <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">5 ★</span>
                </div>
                <div class="review-text">Nadia, WB — রানাঘাট থেকে বলছি, ইউটিউব ওয়াচটাইম ট্রাই করেছিলাম, মনিটাইজেশন অন হয়ে গেছে!</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">40</span>)</button>
            </div>

            <!-- Review 9 -->
            <div class="review-card" data-rating="4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Bikash Tudu <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">4 ★</span>
                </div>
                <div class="review-text">Jhargram, WB — কম দামে কাজ হয়ে যায়, কোনো বড় কমপ্লেন নেই। ২ ঘণ্টার মধ্যেই ফলোয়ার্স এসে গিয়েছিল।</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">10</span>)</button>
            </div>

            <!-- Review 10 -->
            <div class="review-card" data-rating="5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">Karthik Pandian <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">5 ★</span>
                </div>
                <div class="review-text">Coimbatore, TN — Good support team! Solved my payment deposit query within 10 minutes on WhatsApp.</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">29</span>)</button>
            </div>

        </div>

        <!-- Interactive Submission Form -->
        <div class="mt-4 p-3 style-card" style="background: #111827; border: 1px solid #1f2937; border-radius: 16px;">
            <h5 class="fw-bold text-white mb-2" style="font-size: 16px;">Leave a Review</h5>
            <input type="text" id="custName" class="form-control mb-2 bg-dark text-white border-secondary" placeholder="Your Name & Location" style="font-size: 13px;">
            <select id="custRating" class="form-select mb-2 bg-dark text-white border-secondary" style="font-size: 13px;">
                <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                <option value="3">⭐⭐⭐ (3 Stars)</option>
            </select>
            <textarea id="custReview" class="form-control mb-2 bg-dark text-white border-secondary" rows="2" placeholder="Write your experience..." style="font-size: 13px;"></textarea>
            <button class="btn btn-primary w-100 btn-sm font-weight-bold" onclick="submitReview()">Submit Review</button>
        </div>

    </div>

    <!-- Bottom Navigation Bar -->
    <?php include 'components/bottom-nav.php'; ?>

    <script>
        // Interactive Like Function
        function addLike(btn) {
            let countSpan = btn.querySelector('.like-count');
            let currentLikes = parseInt(countSpan.innerText);
            countSpan.innerText = currentLikes + 1;
            btn.style.color = '#38bdf8';
            btn.disabled = true;
        }

        // Filter Reviews Function
        function filterReviews() {
            let filterValue = document.getElementById('reviewFilter').value;
            let cards = document.querySelectorAll('.review-card');

            cards.forEach(card => {
                let rating = card.getAttribute('data-rating');
                if (filterValue === 'all') {
                    card.style.display = 'block';
                } else if (filterValue === 'critical') {
                    if (rating === '3' || rating === '2') {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                } else {
                    if (rating === filterValue) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        }

        // Live Front-End Review Submission
        function submitReview() {
            let name = document.getElementById('custName').value;
            let rating = document.getElementById('custRating').value;
            let reviewText = document.getElementById('custReview').value;

            if(!name || !reviewText) {
                alert('Please enter your name and feedback.');
                return;
            }

            let reviewList = document.getElementById('reviewList');
            let newCard = document.createElement('div');
            newCard.className = 'review-card';
            newCard.setAttribute('data-rating', rating);
            
            newCard.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <span class="customer-name">${name} <span class="verified-badge">✓ Verified Order</span></span>
                    <span class="rating-stars">${rating} ★</span>
                </div>
                <div class="review-text">${reviewText}</div>
                <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">1</span>)</button>
            `;

            reviewList.prepend(newCard);
            document.getElementById('custName').value = '';
            document.getElementById('custReview').value = '';
            alert('Thank you for your feedback! Your review has been added.');
        }
    </script>
</body>
</html>
