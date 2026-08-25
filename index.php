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
            <option value="all">All Ratings (789 Reviews)</option>
            <option value="5">5 Star Only</option>
            <option value="4">4 Star Only</option>
            <option value="critical">3 & 2 Star (Critical Reviews)</option>
        </select>

        <!-- Reviews List (To 10 Selected Mix Reviews) -->
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


            <!-- Review 11 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Srabani Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Howrah, WB — রিলস ভিউস ৫ সেকেন্ডে স্টার্ট হয়ে যায়! ইনস্ট্যান্ট সার্ভিস দেওয়ার জন্য ধন্যবাদ।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 12 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Joyanta Paul <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Midnapore, WB — খড়গপুর থেকে বলছি, ১০০% ট্রাস্টেড প্যানেল। কোনো ঝামেলা ছাড়াই অর্ডার কমপ্লিট হয়ে যায়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">18</span>)</button>
</div>

<!-- Review 13 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debabrata Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Asansol, WB — কম খরচে রিফুয়েল সার্ভিস দারুণ, তবে মাঝে মাঝে রিফিল বাটনে ২-৩ বার ক্লিক করতে হয়।</div>
    <div class="admin-reply"><strong>Admin Response:</strong> Hi Debabrata, please wait 5-10 minutes between refill requests for the server to process smoothly.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">9</span>)</button>
</div>

<!-- Review 14 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sowmya Nair <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Chennai, TN — Very fast delivery for YouTube views! Extremely reliable panel for daily reseller orders.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">21</span>)</button>
</div>

<!-- Review 15 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kavita Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Jaipur, Rajasthan — Followers quality mix thi, par rate ke hisab se service bilkul perfect hai.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 16 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Alok Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Gaya, Bihar — Non drop followers bole the, par 10-15 drop huye. Baad me auto refill work kiya.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">16</span>)</button>
</div>

<!-- Review 17 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Priya Kumari <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Ranchi, Jharkhand — Order complete hone me 2 ghante lage, drops ka issue abhi tak toh nahi aaya.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 18 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Siddharth Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Indore, MP — Cheap SMM panel for daily reseller orders. Instant delivery for most services.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">17</span>)</button>
</div>

<!-- Review 19 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subhasis Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bardhaman, WB — কাল রাতে সার্ভার ডাউন থাকার জন্য একটা অর্ডার ক্যানসেল হয়েছিল, তবে টাকা ওয়ালেটে রিফান্ড হয়ে গেছে।</div>
    <div class="admin-reply"><strong>Admin Response:</strong> System auto-refunds all cancelled orders back to your panel balance instantly.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">13</span>)</button>
</div>

<!-- Review 20 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Farhana Yasmin <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — Park Circus se hoon, Facebook page subscribers fast badhe hain. Very satisfied!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">20</span>)</button>
</div>

<!-- Review 21 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Anirban Dutta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Hooghly, WB — লাইক পেতে কিছুটা দেরি হয়েছিল, তবে কাজ পুরোপুরি কমপ্লিট হয়েছে। ৪ স্টার দিলাম।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">5</span>)</button>
</div>

<!-- Review 22 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Joydeb Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bankura, WB — খাতড়া থেকে বলছি, সার্ভিস ভালো কিন্তু পেমেন্ট পেজে মাঝে মাঝেই স্লো দেখায়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 23 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Neha Singh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Guwahati, Assam — Cheap rates and quick response for reel views. Very smooth experience overall.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 24 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sayani Mukherjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — দাম অনুযায়ী কাজের কোয়ালিটি বেশ ভালো। টুকটাক লেট হওয়া স্বাভাবিক, ভালো প্যানেল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">23</span>)</button>
</div>

<!-- Review 25 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Suresh Behera <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Cuttack, Odisha — Overall performance bohut badhia achhi. Instagram likes fast asuchhi.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
</div>

<!-- Review 26 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rahul Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Siliguri, WB — ওয়াচটাইম সার্ভিসটা ট্রাই করেছিলাম, ৩ দিনের মধ্যে চ্যানেল মনিটাইজেশনের জন্য রেডি হয়ে গেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
</div>

<!-- Review 27 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Ayesha Parveen <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Malda, WB — প্রথমবার ট্রাই করলাম, ৫০ টাকা এড করে ভিউস নিলাম, ২ মিনিটের মধ্যে কাজ হয়ে গেছে!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 28 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Manish Pandey <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Varanasi, UP — Fund add hone me time laga tha, par support me message karte hi balance add ho gaya.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 29 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pooja Banerjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Durgapur, WB — রিটেলারদের জন্য সেরা প্যানেল। কাস্টমার সাপোর্ট দারুণ এবং রেটও খুব কম।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 30 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Deepak Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Kanpur, UP — Auto UPI option best hai, instant payment detect karke balance wallet me aa jata hai.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
    </div>

            <!-- Review 31 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Suman Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Barasat, WB — ইনস্টাগ্রাম লাইক আর রিলস ভিউস ২ মিনিটের মধ্যে স্টার্ট হয়ে গেছে। খুব ট্রাস্টেড সাইট!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 32 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Priya Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — সার্ভিস অনেক ফাস্ট, মাঝে মাঝে কিউআর পেমেন্টে ফান্ড অ্যাড হতে সামান্য লেট হয় তবে কাজ ১০০% হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
</div>

<!-- Review 33 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Amit Mahato <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Purulia, WB — ফেসবুক ওয়াচটাইম প্যাক কমপ্লিট হয়ে গেছে। বঙ্গ বুস্ট প্যানেল সত সত্যি দারুণ!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">24</span>)</button>
</div>

<!-- Review 34 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rajesh Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Patna, Bihar — Speed was a bit slow for Telegram members, but quality is good for this price.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 35 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kalyan Mukherjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Asansol, WB — ইউটিউব সাবস্ক্রাইবার সার্ভিস ট্রাই করলাম, মনিটাইজেশন পেতে কোনো প্রবলেম হয়নি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">31</span>)</button>
</div>

<!-- Review 36 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tanushree Pal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Chinsurah, WB — দাম অনেক কম অন্যান্য প্যানেলের তুলনায়। রিসেলারদের জন্য সেরা অপশন।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
</div>

<!-- Review 37 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Ankit Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bishnupur, WB — অটো ওয়ালেট রিচার্জ সিস্টেম খুব স্মুথ। স্ক্যান করার সাথেই ব্যালেন্স অ্যাড হয়ে যায়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 38 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Vikash Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ranchi, JH — Thoda drop hua tha followers, lekin Refill button press karte hi wapas mil gaya.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 39 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sneha Karmakar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Ranaghat, WB — সাপোর্ট টিম খুব দ্রুত উত্তর দেয়। হোয়াটসঅ্যাপে ৫ মিনিটের মধ্যে প্রবলেম সলভ করে দিয়েছিল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">18</span>)</button>
</div>

<!-- Review 40 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Md. Imran <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Murshidabad, WB — ফেসবুক পেজ ফলোয়ার সার্ভিস ভালো, দামও বেশ সাশ্রয়ী। ধন্যবাদ Bong Boost।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 41 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Riya Sengupta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Suri, WB — আমার বুটিক পেজের জন্য ইন্সটা লাইক নিয়েছিলাম, পোস্ট এনগেজমেন্ট অনেক বেড়ে গেছে!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">29</span>)</button>
</div>

<!-- Review 42 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pankaj Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Lucknow, UP — Highly recommended for SMM resellers. Cheapest rate in the market right now.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 43 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subham Sarkar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Cooch Behar, WB — সার্ভিস ঠিক আছে তবে সার্ভার আপডেটের সময় স্পিড একটু স্লো হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">5</span>)</button>
</div>

<!-- Review 44 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arpita Hazra <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Tamluk, WB — প্রথমবার ট্রাই করলাম ১০০ টাকা দিয়ে, সার্ভিস খুব ফাস্ট লেগেছে। আবার অর্ডার করব।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 45 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arun Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bhubaneswar, Odisha — Best automated SMM panel. Delivery is super smooth and fast.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">20</span>)</button>
</div>

<!-- Review 46 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Monojit Biswas <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Krishnanagar, WB — ফেসবুক ওয়াচটাইমের জন্য বেশ কয়েকবার অর্ডার করেছি, সব কমপ্লিট হয়েছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">16</span>)</button>
</div>

<!-- Review 47 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Srabanti Maiti <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kharagpur, WB — ওয়েবসাইট ডিজাইন খুব সিম্পল আর ফোনে ব্যবহার করা সহজ। গুড সার্ভিস!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 48 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Deepak Yadav <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Varanasi, UP — Instant order start nahi hua tha, 15 min wait karna pada. Baki sab ok tha.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 49 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pratik Chatterjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Serampore, WB — রিলস ভাইরাল করার জন্য ভিউস আর লাইক কম্বো প্যাকটা দারুণ কাজের!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">35</span>)</button>
</div>

<!-- Review 50 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sarla Patel <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Ahmedabad, Gujarat — Affordable price and decent order execution speed. Satisfied.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">10</span>)</button>
</div>

<!-- Review 51 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Babin Mondal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Berhampore, WB — ইউটিউব ভিউসের কোয়ালিটি খুব ভালো, কোনো ড্রপ হয়নি এখনও পর্যন্ত।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">23</span>)</button>
</div>

<!-- Review 52 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rahul Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Bankura, WB — কম খরচে সোশ্যাল মিডিয়া প্রমোট করার সেরা প্যানেল। সার্ভিস দ্রুত দেয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">17</span>)</button>
</div>

<!-- Review 53 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Nisha Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Guwahati, Assam — Instagram followers non-drop service is working amazingly well!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">28</span>)</button>
</div>

<!-- Review 54 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sourav Mallick <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ghatal, WB — সার্ভিস ভালো তবে মাঝে মাঝে সার্ভার বিজি থাকলে ১ ঘণ্টা পর কাজ শুরু হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">9</span>)</button>
</div>

<!-- Review 55 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debjani Som <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — এক কথায় অসাধারণ সাপোর্ট এবং খুব ফাস্ট সার্ভিস ডেলিভারি! ১০ এ ১০ দেব।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">42</span>)</button>
</div>

            <!-- Review 56 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arindam Paul <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Siliguri, WB — ইনস্টাগ্রাম রিলস ভিউস একদম পলক ফেলতে না ফেলতেই চলে এলো! সত্যিই অসাধারণ সার্ভিস।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
</div>

<!-- Review 57 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Srabani Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — সার্ভিস খুব ভালো, তবে মাঝে মধ্যে নোটিফিকেশন পেতে দেরি হয়। অল ওভার বেশ সন্তোষজনক।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 58 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rakesh Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Ranchi, JH — Very fast automatic fund addition via UPI QR. Service started within 1 minute!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">31</span>)</button>
</div>

<!-- Review 59 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Bishnu Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bankura, WB — ছাতনা থেকে বলছি, ফলোয়ার্স কিছুটা ড্রপ হয়েছিল তবে রিফিল অপশন প্রেস করার পর আবার চলে আসে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 60 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pooja Kumari <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Patna, Bihar — Cheapest price for YouTube watch time service. Channel got monetized successfully!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">45</span>)</button>
</div>

<!-- Review 61 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subham Dey <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Howrah, WB — ফেসবুক পেজ লাইকের জন্য ট্রাই করেছিলাম, ৩ ঘণ্টার মধ্যে অর্ডার কমপ্লিট। ভালো অভিজ্ঞতা।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">16</span>)</button>
</div>

<!-- Review 62 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tanmoy Biswas <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Nadia, WB — কৃষ্ণনগর থেকে বলছি, এসএমএম রিসেলিংয়ের জন্য Bong Boost সেরা প্যানেল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 63 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Md. Sahil <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Asansol, WB — Telegram member order took 2 hours to start. Speed could be improved.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 64 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Ananya Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Durgapur, WB — ওয়াটসঅ্যাপে সাপোর্ট মেসেজ করার কয়েক মিনিটের মধ্যেই হেল্প পেয়েছি। গ্রেট সার্ভিস!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 65 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Vikram Singh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Jaipur, RJ — Good non-drop Instagram followers service at a pocket-friendly price.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 66 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Dipankar Hazra <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Purulia, WB — পুরুলিয়া থেকে রিলস ভিউস প্যাক নিলাম, একদম ইনস্ট্যান্ট ডেলিভারি পেয়েছি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 67 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kavita Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Lucknow, UP — Balance auto added fastly. Panel interface is clean and user-friendly.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 68 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Swapan Ghosal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Burdwan, WB — ইউটিউব সাবস্ক্রাইবার খুব স্পিডে বাড়ছে, কম টাকায় একদম অরিজিনাল কোয়ালিটি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">20</span>)</button>
</div>

<!-- Review 69 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Aman Gupta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Gaya, Bihar — Payment stuck in processing once, but admin manual refund gave balance back.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">9</span>)</button>
</div>

<!-- Review 70 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Mithun Chakraborty <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Malda, WB — প্রথম দিন ৫০ টাকা অ্যাড করে ট্রাই করেছিলাম, এখন রেগুলার কাস্টমার হয়ে গেছি!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">38</span>)</button>
</div>

<!-- Review 71 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sujata Sen <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Hooghly, WB — লাইক প্রমোশনের জন্য বেশ কাজের প্যানেল। খুব কম দামেই কাজ হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
</div>

<!-- Review 72 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rahul Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Barasat, WB — এপিআই ইন্টিগ্রেশন করে আমার নিজস্ব প্যানেলে কানেক্ট করেছি, সার্ভিস রিয়েল টাইম চলে!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">29</span>)</button>
</div>

<!-- Review 73 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Nitin Reddy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Hyderabad, TS — Average speed for views, but pricing is very competitive.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 74 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tuli Mondal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Cooch Behar, WB — আমার শপের ফেসবুক পেজে ফলোয়ার প্রমোট করিয়েছিলাম, রেসপন্স খুব ভালো।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">21</span>)</button>
</div>

<!-- Review 75 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Alok Sarkar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Jalpaiguri, WB — উত্তরবঙ্গ থেকে ইউজ করছি, অর্ডার ডেলিভারি স্পিড বেশ ফাস্ট।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">13</span>)</button>
</div>

<!-- Review 76 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sneha Mondal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Midnapore, WB — মেদিনীপুর থেকে বলছি, অটো রিচার্জ সিস্টেম আর সার্ভিস ডেলিভারি দুটোই সেরা!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">36</span>)</button>
</div>

<!-- Review 77 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Deepak Jha <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Darbhanga, Bihar — Very smooth website for mobile users. Instant status updates.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">10</span>)</button>
</div>

<!-- Review 78 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Soumya Banerjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — ইউটিউব ওয়াচটাইম ৩ দিনের মধ্যে কমপ্লিট! একদম ১০০% ট্রাস্টেড সার্ভিস।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">41</span>)</button>
</div>

<!-- Review 79 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rabi Soren <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Jhargram, WB — ঝাড়গ্রাম থেকে বলছি, কিছু ফলোয়ার্স ড্রপ হয়েছিল, তবে সাপোর্ট টীম রিফিল করে দিয়েছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">5</span>)</button>
</div>

<!-- Review 80 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Farhin Sultana <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Murshidabad, WB — ইনস্টাগ্রাম রিলসের জন্য এই প্যানেলের চেয়ে কম দামে আর কোথাও সার্ভিস পাওয়া যাবে না।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">24</span>)</button>
</div>

<!-- Review 81 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Manish Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Varanasi, UP — Cheap price and genuine response. Will definitely recommend to reseller friends.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">18</span>)</button>
</div>

<!-- Review 82 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Prosenjit Chatterjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Habra, WB — মাত্র ৫০ টাকায় ট্রাই করেছিলাম, ইনস্ট্যান্ট ভিউস এসে গেছে। সেরা অভিজ্ঞতা!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">30</span>)</button>
</div>

<!-- Review 83 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Priyanka Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Tamluk, WB — পেজ প্রোমোশন খুব ভালো হয়েছে। কাজ দ্রুত করার জন্য ধন্যবাদ।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 84 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Srikant Behera <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Cuttack, Odisha — Fast speed for YouTube views and auto refill facility works fine.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 85 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arup Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bankura, WB — বাঁকুড়া থেকে বলছি, ফান্ড অ্যাড হওয়ার সময় ২ মিনিট ওয়েট করতে হয়েছিল। সার্ভিস ওকে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 86 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kalyani Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — ফ্যাশন ব্লগের জন্য ইনস্টাগ্রাম লাইক নিয়েছিলাম, এনগেজমেন্ট খুব ভালো এসেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">35</span>)</button>
</div>

<!-- Review 87 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Anuj Mishra <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Kanpur, UP — Order completed within reasonable time. Cheap rate compared to other panels.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 88 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Biswajit Pal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Suri, WB — সত্যি বলতে একদম জেনুইন সার্ভিস! কোনো ফ্রড বা ফেক জিনিস নেই।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 89 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kunal Shah <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ahmedabad, GJ — Speed was slightly slow during weekend hours, but non-drop.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 90 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Ritu Ganguly <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Durgapur, WB — রিলস বুস্টিং সার্ভিসের জন্য Bong Boost সবসময় আমার ফার্স্ট চয়েস।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 91 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Santanu Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Ranaghat, WB — কম খরচে দ্রুত ফলোয়ার বাড়ানোর জন্য সেরা প্ল্যাটফর্ম।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">17</span>)</button>
</div>

<!-- Review 92 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Neha Parveen <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — পার্ক সার্কাস থেকে বলছি, ফেসবুক ওয়াচটাইমের কাজ খুব দ্রুত হয়ে গেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">25</span>)</button>
</div>

<!-- Review 106 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subhadip Ghosal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Asansol, WB — ইনস্টাগ্রাম লাইক এক সেকেন্ডে রিফ্লেক্ট হয়ে গেছে! দারুণ স্পিড।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 107 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Priyanka Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Siliguri, WB — উত্তরবঙ্গ থেকে সার্ভিস নিচ্ছি, কাজের কোয়ালিটি বেশ স্যাটিস্ফাইং।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">9</span>)</button>
</div>

<!-- Review 108 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rohit Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Patna, Bihar — Best SMM panel for YouTube view packages. Affordable and genuine.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 109 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sajal Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bankura, WB — মেজিয়া থেকে বলছি, পেমেন্ট অ্যাড হতে একটু টাইম লেগেছিল কিন্তু কাজ হয়ে গেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 110 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Swati Mukherjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — ফেসবুক পেজ ফলোয়ার নিয়েছিলাম, ৩ ঘণ্টার মধ্যে অর্ডার কমপ্লিট হয়ে গেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">31</span>)</button>
</div>

<!-- Review 111 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Amitabh Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Durgapur, WB — ইউটিউব ওয়াচটাইম প্যাকের জন্য ভালো সাইট। সার্ভিস রেট অনেক কম।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">18</span>)</button>
</div>

<!-- Review 112 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Anil Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Ranchi, JH — Telegram members service is super fast! Highly recommended.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">25</span>)</button>
</div>

<!-- Review 113 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tanumoy Paul <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Howrah, WB — স্পিড কিছুটা স্লো ছিল, তবে সাপোর্ট টিম হোয়াটসঅ্যাপে বেশ হেল্পফুল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 114 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Madhuri Sen <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bardhaman, WB — রিলস ভাইরাল বুস্টিংয়ের জন্য সবচেয়ে ভরসাযোগ্য প্যানেল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">29</span>)</button>
</div>

<!-- Review 115 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rajiv Pandey <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Varanasi, UP — Automated system works great. Wallet balance added instantly.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 116 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Manoj Mahato <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Purulia, WB — পুরুলিয়া থেকে রিলস ভিউস নিলাম, ২ মিনিটের মধ্যে অল ডান!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">20</span>)</button>
</div>

<!-- Review 117 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sneha Karmakar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Malda, WB — প্রথমবার ৫০ টাকা রিচার্জ করে ট্রাই করেছিলাম, খুব ভালো লেগেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
</div>

<!-- Review 118 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kavita Reddy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Hyderabad, TS — Very cheap price for Instagram followers non-drop pack.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 119 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arup Biswas <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Nadia, WB — কিছু ফলোয়ার ড্রপ হয়েছিল, তবে রিফিল অপশন কাজ করেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">5</span>)</button>
</div>

<!-- Review 120 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sarmistha Bag <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Hooghly, WB — ইউটিউব মনিটাইজেশন প্যাকের জন্য সেরা প্যানেল। কোনো প্রবলেম হয়নি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 121 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Deepak Singh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Lucknow, UP — Good service quality. Reseller friendly interface.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 122 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Soumen Maiti <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kharagpur, WB — মেদিনীপুর এরিয়াতে এমন ফাস্ট সার্ভিস আর কোথাও পাইনি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">24</span>)</button>
</div>

<!-- Review 123 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Manish Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Gaya, Bihar — Server was busy in evening, but order completed later in night.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 124 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debjani Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — আমার শপের পেজ বুস্ট করার পর সেলস অনেক বেড়েছে! থ্যাঙ্কস।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">36</span>)</button>
</div>

<!-- Review 125 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Alok Nath <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Bhubaneswar, OD — Smooth transaction and instant views delivery.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">13</span>)</button>
</div>

<!-- Review 126 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subhasis Dutta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Suri, WB — অটো কিউআর পেমেন্ট ফিচারটা আমার সবচেয়ে ভালো লেগেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">21</span>)</button>
</div>

<!-- Review 127 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pooja Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Jaipur, RJ — Nice panel for social media promotion. Very easy to use.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">10</span>)</button>
</div>

<!-- Review 128 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Anupam Mondal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Berhampore, WB — ১০০% রিয়েল ও ট্রাস্টেড সাইট। কোনো ধরণের ফ্রড নেই।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">30</span>)</button>
</div>

<!-- Review 129 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rajesh Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bankura, WB — ওন্দা থেকে বলছি, সার্ভিস ঠিকঠাক কিন্তু রেটগুলো আরেকটু কমালে ভালো হতো।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">4</span>)</button>
</div>

<!-- Review 130 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Riya Sengupta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Barasat, WB — ফেসবুক রিলস ভিউসের সাথে সাথে এনগেজমেন্টও খুব ভালো আসে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">23</span>)</button>
</div>

<!-- Review 131 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Vikram Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Guwahati, AS — Instant start service for Instagram reel likes. Good experience.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">16</span>)</button>
</div>

<!-- Review 132 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tarun Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Ranaghat, WB — রিসেলিং করে মাসে ভালো টাকা ইনকাম হচ্ছে এই প্যানেল দিয়ে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">38</span>)</button>
</div>

<!-- Review 133 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Suresh Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Kanpur, UP — Order status updating was a bit delayed, but work completed.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 134 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Mouita Chatterjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — অনলাইন শপ প্রমোট করার জন্য Bong Boost আমার একমাত্র ভরসা।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">26</span>)</button>
</div>

<!-- Review 135 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sujay Paul <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Chinsurah, WB — ইউটিউব চ্যানেল সাবস্ক্রাইবার সার্ভিস খুব স্মুথলি কাজ করেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 136 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Aarti Kumari <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Darbhanga, BH — Fast refill button facility available. Excellent service!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
</div>

<!-- Review 137 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Mithun Chakraborty <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Cooch Behar, WB — দাম অনুযায়ী কাজের স্পিড এবং সাপোর্ট চমৎকার।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 138 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Nisha Parveen <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Murshidabad, WB — ইনস্টাগ্রামের নন-ড্রপ ফলোয়ার্স সত্যি ১টাও ড্রপ হয়নি!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">28</span>)</button>
</div>

<!-- Review 139 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pranab Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Jalpaiguri, WB — রাতে অর্ডার করার কারণে কাজ সকালে স্টার্ট হয়েছিল। তবে ওকে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 140 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Ankita Dey <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Serampore, WB — কম খরচে সোশ্যাল মিডিয়া গ্রো করার সবচেয়ে বিশ্বাসযোগ্য সাইট।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">34</span>)</button>
</div>

<!-- Review 141 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rakesh Yadav <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Patna, BH — Affordable prices for daily resellers. Order speed is fine.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 142 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debabrata Hazra <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Tamluk, WB — সাপোর্ট টিম খুব ফ্রেন্ডলি, যেকোনো প্রবলেম খুব তাড়াতাড়ি মিটিয়ে দেয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 143 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Suman Sarkar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Krishnanagar, WB — প্রথম অর্ডারে দারুণ সার্ভিস পেলাম, আবার অর্ডার করার প্ল্যান আছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">17</span>)</button>
</div>

<!-- Review 31 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Suman Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Barasat, WB — ইনস্টাগ্রাম লাইক আর রিলস ভিউস ২ মিনিটের মধ্যে স্টার্ট হয়ে গেছে। খুব ট্রাস্টেড সাইট!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 32 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Priya Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — সার্ভিস অনেক ফাস্ট, মাঝে মাঝে কিউআর পেমেন্টে ফান্ড অ্যাড হতে সামান্য লেট হয় তবে কাজ ১০০% হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
</div>

<!-- Review 33 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Amit Mahato <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Purulia, WB — ফেসবুক ওয়াচটাইম প্যাক কমপ্লিট হয়ে গেছে। বঙ্গ বুস্ট প্যানেল সত সত্যি দারুণ!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">24</span>)</button>
</div>

<!-- Review 34 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rajesh Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Patna, Bihar — Speed was a bit slow for Telegram members, but quality is good for this price.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 35 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kalyan Mukherjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Asansol, WB — ইউটিউব সাবস্ক্রাইবার সার্ভিস ট্রাই করলাম, মনিটাইজেশন পেতে কোনো প্রবলেম হয়নি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">31</span>)</button>
</div>

<!-- Review 36 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tanushree Pal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Chinsurah, WB — দাম অনেক কম অন্যান্য প্যানেলের তুলনায়। রিসেলারদের জন্য সেরা অপশন।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
</div>

<!-- Review 37 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Ankit Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bishnupur, WB — অটো ওয়ালেট রিচার্জ সিস্টেম খুব স্মুথ। স্ক্যান করার সাথেই ব্যালেন্স অ্যাড হয়ে যায়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 38 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Vikash Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ranchi, JH — Thoda drop hua tha followers, lekin Refill button press karte hi wapas mil gaya.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 39 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sneha Karmakar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Ranaghat, WB — সাপোর্ট টিম খুব দ্রুত উত্তর দেয়। হোয়াটসঅ্যাপে ৫ মিনিটের মধ্যে প্রবলেম সলভ করে দিয়েছিল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">18</span>)</button>
</div>

<!-- Review 40 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Md. Imran <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Murshidabad, WB — ফেসবুক পেজ ফলোয়ার সার্ভিস ভালো, দামও বেশ সাশ্রয়ী। ধন্যবাদ Bong Boost।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 41 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Riya Sengupta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Suri, WB — আমার বুটিক পেজের জন্য ইন্সটা লাইক নিয়েছিলাম, পোস্ট এনগেজমেন্ট অনেক বেড়ে গেছে!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">29</span>)</button>
</div>

<!-- Review 42 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pankaj Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Lucknow, UP — Highly recommended for SMM resellers. Cheapest rate in the market right now.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 43 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subham Sarkar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Cooch Behar, WB — সার্ভিস ঠিক আছে তবে সার্ভার আপডেটের সময় স্পিড একটু স্লো হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">5</span>)</button>
</div>

<!-- Review 44 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arpita Hazra <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Tamluk, WB — প্রথমবার ট্রাই করলাম ১০০ টাকা দিয়ে, সার্ভিস খুব ফাস্ট লেগেছে। আবার অর্ডার করব।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 45 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arun Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bhubaneswar, Odisha — Best automated SMM panel. Delivery is super smooth and fast.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">20</span>)</button>
</div>

<!-- Review 46 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Monojit Biswas <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Krishnanagar, WB — ফেসবুক ওয়াচটাইমের জন্য বেশ কয়েকবার অর্ডার করেছি, সব কমপ্লিট হয়েছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">16</span>)</button>
</div>

<!-- Review 47 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Srabanti Maiti <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kharagpur, WB — ওয়েবসাইট ডিজাইন খুব সিম্পল আর ফোনে ব্যবহার করা সহজ। গুড সার্ভিস!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 48 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Deepak Yadav <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Varanasi, UP — Instant order start nahi hua tha, 15 min wait karna pada. Baki sab ok tha.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 49 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pratik Chatterjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Serampore, WB — রিলস ভাইরাল করার জন্য ভিউস আর লাইক কম্বো প্যাকটা দারুণ কাজের!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">35</span>)</button>
</div>

<!-- Review 50 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sarla Patel <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Ahmedabad, Gujarat — Affordable price and decent order execution speed. Satisfied.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">10</span>)</button>
</div>

<!-- Review 51 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Babin Mondal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Berhampore, WB — ইউটিউব ভিউসের কোয়ালিটি খুব ভালো, কোনো ড্রপ হয়নি এখনও পর্যন্ত।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">23</span>)</button>
</div>

<!-- Review 52 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rahul Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Bankura, WB — কম খরচে সোশ্যাল মিডিয়া প্রমোট করার সেরা প্যানেল। সার্ভিস দ্রুত দেয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">17</span>)</button>
</div>

<!-- Review 53 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Nisha Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Guwahati, Assam — Instagram followers non-drop service is working amazingly well!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">28</span>)</button>
</div>

<!-- Review 54 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sourav Mallick <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ghatal, WB — সার্ভিস ভালো তবে মাঝে মাঝে সার্ভার বিজি থাকলে ১ ঘণ্টা পর কাজ শুরু হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">9</span>)</button>
</div>

<!-- Review 55 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debjani Som <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — এক কথায় অসাধারণ সাপোর্ট এবং খুব ফাস্ট সার্ভিস ডেলিভারি! ১০ এ ১০ দেব।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">42</span>)</button>
    </div>
        <!-- Review 31 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Suman Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Barasat, WB — ইনস্টাগ্রাম লাইক আর রিলস ভিউস ২ মিনিটের মধ্যে স্টার্ট হয়ে গেছে। খুব ট্রাস্টেড সাইট!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 32 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Priya Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — সার্ভিস অনেক ফাস্ট, মাঝে মাঝে কিউআর পেমেন্টে ফান্ড অ্যাড হতে সামান্য লেট হয় তবে কাজ ১০০% হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
</div>

<!-- Review 33 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Amit Mahato <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Purulia, WB — ফেসবুক ওয়াচটাইম প্যাক কমপ্লিট হয়ে গেছে। বঙ্গ বুস্ট প্যানেল সত সত্যি দারুণ!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">24</span>)</button>
</div>

<!-- Review 34 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rajesh Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Patna, Bihar — Speed was a bit slow for Telegram members, but quality is good for this price.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 35 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kalyan Mukherjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Asansol, WB — ইউটিউব সাবস্ক্রাইবার সার্ভিস ট্রাই করলাম, মনিটাইজেশন পেতে কোনো প্রবলেম হয়নি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">31</span>)</button>
</div>

<!-- Review 36 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tanushree Pal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Chinsurah, WB — দাম অনেক কম অন্যান্য প্যানেলের তুলনায়। রিসেলারদের জন্য সেরা অপশন।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
</div>

<!-- Review 37 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Ankit Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bishnupur, WB — অটো ওয়ালেট রিচার্জ সিস্টেম খুব স্মুথ। স্ক্যান করার সাথেই ব্যালেন্স অ্যাড হয়ে যায়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 38 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Vikash Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ranchi, JH — Thoda drop hua tha followers, lekin Refill button press karte hi wapas mil gaya.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 39 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sneha Karmakar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Ranaghat, WB — সাপোর্ট টিম খুব দ্রুত উত্তর দেয়। হোয়াটসঅ্যাপে ৫ মিনিটের মধ্যে প্রবলেম সলভ করে দিয়েছিল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">18</span>)</button>
</div>

<!-- Review 40 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Md. Imran <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Murshidabad, WB — ফেসবুক পেজ ফলোয়ার সার্ভিস ভালো, দামও বেশ সাশ্রয়ী। ধন্যবাদ Bong Boost।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 41 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Riya Sengupta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Suri, WB — আমার বুটিক পেজের জন্য ইন্সটা লাইক নিয়েছিলাম, পোস্ট এনগেজমেন্ট অনেক বেড়ে গেছে!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">29</span>)</button>
</div>

<!-- Review 42 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pankaj Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Lucknow, UP — Highly recommended for SMM resellers. Cheapest rate in the market right now.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 43 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subham Sarkar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Cooch Behar, WB — সার্ভিস ঠিক আছে তবে সার্ভার আপডেটের সময় স্পিড একটু স্লো হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">5</span>)</button>
</div>

<!-- Review 44 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arpita Hazra <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Tamluk, WB — প্রথমবার ট্রাই করলাম ১০০ টাকা দিয়ে, সার্ভিস খুব ফাস্ট লেগেছে। আবার অর্ডার করব।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 45 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arun Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bhubaneswar, Odisha — Best automated SMM panel. Delivery is super smooth and fast.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">20</span>)</button>
</div>

<!-- Review 46 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Monojit Biswas <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Krishnanagar, WB — ফেসবুক ওয়াচটাইমের জন্য বেশ কয়েকবার অর্ডার করেছি, সব কমপ্লিট হয়েছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">16</span>)</button>
</div>

<!-- Review 47 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Srabanti Maiti <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kharagpur, WB — ওয়েবসাইট ডিজাইন খুব সিম্পল আর ফোনে ব্যবহার করা সহজ। গুড সার্ভিস!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 48 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Deepak Yadav <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Varanasi, UP — Instant order start nahi hua tha, 15 min wait karna pada. Baki sab ok tha.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 49 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pratik Chatterjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Serampore, WB — রিলস ভাইরাল করার জন্য ভিউস আর লাইক কম্বো প্যাকটা দারুণ কাজের!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">35</span>)</button>
</div>

<!-- Review 50 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sarla Patel <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Ahmedabad, Gujarat — Affordable price and decent order execution speed. Satisfied.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">10</span>)</button>
</div>

<!-- Review 51 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Babin Mondal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Berhampore, WB — ইউটিউব ভিউসের কোয়ালিটি খুব ভালো, কোনো ড্রপ হয়নি এখনও পর্যন্ত।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">23</span>)</button>
</div>

<!-- Review 52 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rahul Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Bankura, WB — কম খরচে সোশ্যাল মিডিয়া প্রমোট করার সেরা প্যানেল। সার্ভিস দ্রুত দেয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">17</span>)</button>
</div>

<!-- Review 53 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Nisha Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Guwahati, Assam — Instagram followers non-drop service is working amazingly well!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">28</span>)</button>
</div>

<!-- Review 54 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sourav Mallick <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ghatal, WB — সার্ভিস ভালো তবে মাঝে মাঝে সার্ভার বিজি থাকলে ১ ঘণ্টা পর কাজ শুরু হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">9</span>)</button>
</div>

<!-- Review 55 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debjani Som <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — এক কথায় অসাধারণ সাপোর্ট এবং খুব ফাস্ট সার্ভিস ডেলিভারি! ১০ এ ১০ দেব।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">42</span>)</button>
</div>
<!-- Review 151 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sabyasachi Mukherjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — ইউটিউব শর্টস ভিউস একদম সেকেন্ডের মধ্যে স্টার্ট হয়ে গেছে। হেব্বি সার্ভিস!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">28</span>)</button>
</div>

<!-- Review 152 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pujarini Paul <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Siliguri, WB — উত্তরবঙ্গ থেকে বলছি, ফেসবুক পেজ প্রমোশনের কাজ খুব স্মুথলি কমপ্লিট হয়েছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">15</span>)</button>
</div>

<!-- Review 153 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rakesh Mahato <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Purulia, WB — পুরুলিয়া থেকে রিলস ভিউস প্যাক নিলাম, একদম ১০০% জেনুইন সার্ভিস!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">32</span>)</button>
</div>

<!-- Review 154 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kartik Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bankura, WB — ছাতনা থেকে বলছি, ফান্ড অ্যাড করার সময় কিউআর স্ক্যানে সামান্য লেট হয়েছিল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 155 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Srikant Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Patna, Bihar — Non-drop Instagram followers service works like magic. Very cheap rate!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">41</span>)</button>
</div>

<!-- Review 156 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subarna Ghosh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Howrah, WB — সাপোর্ট টিম হোয়াটসঅ্যাপে খুব ফাস্ট রেসপন্স করে। সার্ভিস ভালো।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">19</span>)</button>
</div>

<!-- Review 157 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debasis Mondal <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Asansol, WB — ইউটিউব ওয়াচটাইম ৩ দিনের মধ্যে ডান! চ্যানেল মনিটাইজ হয়ে গেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">36</span>)</button>
</div>

<!-- Review 158 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Alok Kumar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Ranchi, JH — Delivery was slightly slow on Sunday evening, but completed successfully.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">6</span>)</button>
</div>

<!-- Review 159 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tanushree Roy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Durgapur, WB — আমার শপের পেজে ফলোয়ার প্রমোট করিয়ে দারুণ রেজাল্ট পেয়েছি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">24</span>)</button>
</div>

<!-- Review 160 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Vikash Sharma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Jaipur, RJ — Automatic balance addition feature is very fast. Good experience.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">12</span>)</button>
</div>

<!-- Review 161 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sumanta Hazra <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Burdwan, WB — রিলস ভাইরাল বুস্টিংয়ের জন্য Bong Boost সবসময় সেরা প্যানেল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">30</span>)</button>
</div>

<!-- Review 162 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Farhin Sultana <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Murshidabad, WB — কম খরচে সোশ্যাল মিডিয়া প্রমোট করার জন্য দারুণ মাধ্যম।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">17</span>)</button>
</div>

<!-- Review 163 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Priyanka Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Malda, WB — প্রথমবার ৫০ টাকা দিয়ে ট্রাই করেছিলাম, সার্ভিস দেখে আমি ইমপ্রেসড!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">39</span>)</button>
</div>

<!-- Review 164 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Md. Arif <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Gaya, Bihar — Telegram member speed was average, overall service is satisfactory.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">8</span>)</button>
</div>

<!-- Review 165 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Gourab Paul <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Nadia, WB — কৃষ্ণনগর থেকে বলছি, এপিআই ইন্টিগ্রেশন খুব সুন্দর কাজ করছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">22</span>)</button>
</div>

<!-- Review 166 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kavita Gupta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Lucknow, UP — Clean UI, smooth ordering process and instant refill support.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 167 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Tapan Dey <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Hooghly, WB — ইন্সটাগ্রাম লাইক এক ক্লিকেই চলে আসে। স্পিড নিয়ে কোনো কথা হবে না!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">27</span>)</button>
</div>

<!-- Review 168 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sourav Mallick <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Midnapore, WB — ১০-১৫টি ফলোয়ার ড্রপ হয়েছিল, তবে রিফিল বাটন মারতেই চলে আসে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">5</span>)</button>
</div>

<!-- Review 169 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arpita Chatterji <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — অনলাইন বুটিক পেজের লাইক বাড়াতে খুব সাহায্য করেছে এই প্যানেল।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">33</span>)</button>
</div>

<!-- Review 170 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Deepak Jha <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Darbhanga, Bihar — Reliable SMM panel for small digital marketing agencies.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">14</span>)</button>
</div>

<!-- Review 171 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Prasenjit Sarkar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Suri, WB — অটোমেটিক পেমেন্ট সিস্টেম থাকায় ওয়ালেটে রিচার্জ করা খুব সহজ।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">26</span>)</button>
</div>

<!-- Review 172 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Aarti Singh <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Varanasi, UP — Instant delivery for Instagram reel views pack. Great value.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">18</span>)</button>
</div>

<!-- Review 173 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Subham Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Bankura, WB — ওন্দা থেকে বলছি, সার্ভিস খুব ফাস্ট এবং ১০০% স্যাটিসফ্যাক্টরি!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">45</span>)</button>
</div>

<!-- Review 174 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Rabi Soren <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Jhargram, WB — সার্ভার আপডেটের জন্য ১৫ মিনিট কাজ বন্ধ ছিল, পরে কমপ্লিট হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">4</span>)</button>
</div>

<!-- Review 175 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Nisha Karmakar <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Barasat, WB — ফেসবুক রিলস বুস্টিংয়ের জন্য কোনো তুলনা হয় না এই সাইটের।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">29</span>)</button>
</div>

<!-- Review 176 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Suresh Behera <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Cuttack, Odisha — Good speed for YouTube subscribers pack. Fully satisfied.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">13</span>)</button>
</div>

<!-- Review 177 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Mithun Biswas <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Ranaghat, WB — ডেইলি অর্ডার করি কাস্টমারদের জন্য, কোনোদিন কমপ্লেন আসেনি।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">37</span>)</button>
</div>

<!-- Review 178 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Kunal Yadav <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Kanpur, UP — Order placed late night, started next morning. Service quality fine.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">7</span>)</button>
</div>

<!-- Review 179 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Sayani Sengupta <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Serampore, WB — কম খরচে দ্রুত পেজের এনগেজমেন্ট বাড়াতে চাইলে এটাই সেরা।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">21</span>)</button>
</div>

<!-- Review 180 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Anuj Reddy <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Hyderabad, TS — Smooth panel UI and instant auto QR payment facility.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">16</span>)</button>
</div>

<!-- Review 181 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Debarati Das <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Kolkata, WB — ওয়াচটাইম সার্ভিস নিয়েছিলাম, ৩ দিনে চ্যানেলের কাজ কমপ্লিট।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">42</span>)</button>
</div>

<!-- Review 182 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pratik Mahato <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Jalpaiguri, WB — ইনস্টাগ্রাম নন-ড্রপ ফলোয়ার্স একদম পারফেক্ট কাজ করেছে।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">10</span>)</button>
</div>

<!-- Review 183 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Nitin Verma <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Indore, MP — Very fast delivery and excellent customer support response!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">25</span>)</button>
</div>

<!-- Review 184 -->
<div class="review-card" data-rating="3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Bipin Bauri <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">3 ★</span>
    </div>
    <div class="review-text">Bankura, WB — খাতড়া থেকে বলছি, সার্ভিস ফাস্ট কিন্তু দাম আরেকটু কম হলে ভালো হয়।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">9</span>)</button>
</div>

<!-- Review 185 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Pooja Banerjee <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Chinsurah, WB — সোশ্যাল মিডিয়া গ্রোথ সার্ভিসের জন্য ১০০% ট্রাস্টেড প্ল্যাটফর্ম।</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">31</span>)</button>
</div>

<!-- Review 186 -->
<div class="review-card" data-rating="4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Santosh Patel <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">4 ★</span>
    </div>
    <div class="review-text">Ahmedabad, GJ — Cheap pricing for SMM resellers. Delivery speed is good.</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">11</span>)</button>
</div>

<!-- Review 187 -->
<div class="review-card" data-rating="5">
    <div class="d-flex justify-content-between align-items-center">
        <span class="customer-name">Arup Mallick <span class="verified-badge">✓ Verified Order</span></span>
        <span class="rating-stars">5 ★</span>
    </div>
    <div class="review-text">Berhampore, WB — রিলস ভিউস একদম পলক ফেলার আগেই চলে এসেছে!</div>
    <button class="helpful-btn" onclick="addLike(this)">👍 Helpful (<span class="like-count">23</span>)</button>
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
