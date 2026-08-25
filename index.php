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
        .customer-name {
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
        .rating {
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

    <!-- Customer Reviews Section (Top 20 Reviews) -->
    <div class="reviews-container">
        <h3 class="text-center fw-bold mb-4" style="color: #ffffff;">Customer Reviews</h3>

        <!-- Review 1 -->
        <div class="review-card">
            <h4 class="customer-name">Priyanka Das</h4>
            <p class="review-text">Bankura, WB — বাঁকুড়া থেকে বলছি, আমার ইউটিউব চ্যানেলের ভিউস আর সাবস্ক্রাইবার খুব দ্রুত কমপ্লিট হয়ে গেছে! সত্যি দারুণ সার্ভিস, থ্যাঙ্ক ইউ Bong Boost!</p>
            <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>

        <!-- Review 2 -->
        <div class="review-card">
            <h4 class="customer-name">Rohan Sen</h4>
            <p class="review-text">Kolkata, WB — সার্ভিস অনেক ফাস্ট, তবে কাল সন্ধ্যায় কিউআর পেমেন্টে টাকা অ্যাড হতে ৫ মিনিট সময় লেগেছিল। বাকি সব ভালো।</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

        <!-- Review 3 -->
        <div class="review-card">
            <h4 class="customer-name">Sowmya Nair</h4>
            <p class="review-text">Chennai, TN — Instagram reels views standard is good, but response from WhatsApp team was a bit late.</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

        <!-- Review 4 -->
        <div class="review-card">
            <h4 class="customer-name">Amitabh Hazra</h4>
            <p class="review-text">Purulia, WB — পুরুলিয়া থেকে বলছি, ফেসবুক ওয়াচটাইম প্যাক কমপ্লিট হতে ৩ দিন লেগেছিল। সার্ভিস চালু থাকে কিন্তু গতি আরও বাড়ানো উচিত।</p>
            <div class="rating">⭐⭐⭐</div>
        </div>

        <!-- Review 5 -->
        <div class="review-card">
            <h4 class="customer-name">Shagufta Parveen</h4>
            <p class="review-text">Lucknow, UP — Telegram members service me kuch drop hua tha, par refill button dabate hi instant wapas aa gaya.</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

        <!-- Review 6 -->
        <div class="review-card">
            <h4 class="customer-name">Subhasis Ghosh</h4>
            <p class="review-text">Bardhaman, WB — কাল রাতে সার্ভার ডাউন থাকার জন্য একটা অর্ডার ক্যানসেল হয়েছিল, তবে টাকা অটো রিফান্ড হয়ে গেছে।</p>
            <div class="rating">⭐⭐⭐</div>
        </div>

        <!-- Review 7 -->
        <div class="review-card">
            <h4 class="customer-name">Aarti Gupta</h4>
            <p class="review-text">Delhi — Very low rates for Instagram likes! My online boutique post got very good engagement.</p>
            <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>

        <!-- Review 8 -->
        <div class="review-card">
            <h4 class="customer-name">Vikram Yadav</h4>
            <p class="review-text">Patna, Bihar — UPI Auto add fund perfectly kaam karta hai. Panel ka interface bhi bohot simple hai.</p>
            <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>

        <!-- Review 9 -->
        <div class="review-card">
            <h4 class="customer-name">Srabani Roy</h4>
            <p class="review-text">Howrah, WB — রিলস ভিউস ৫ সেকেন্ডে স্টার্ট হয়ে যায়, কিন্তু ফলোয়ার আসতে ১০-১৫ মিনিট দেরি হয়।</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

        <!-- Review 10 -->
        <div class="review-card">
            <h4 class="customer-name">Debabrata Bauri</h4>
            <p class="review-text">Asansol, WB — কম খরচে রিফুয়েল সার্ভিস দারুণ, তবে মাঝে মাঝে রিফিল বাটনে ২-৩ বার ক্লিক করতে হয়।</p>
            <div class="rating">⭐⭐⭐</div>
        </div>

        <!-- Review 11 -->
        <div class="review-card">
            <h4 class="customer-name">Kavita Sharma</h4>
            <p class="review-text">Jaipur, Rajasthan — Followers quality mix thi, par rate ke hisab se service bilkul perfect hai.</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

        <!-- Review 12 -->
        <div class="review-card">
            <h4 class="customer-name">Moumita Chakraborty</h4>
            <p class="review-text">Siliguri, WB — উত্তরবঙ্গ থেকে বলছি, পেজের লাইক প্রমোশন করিয়েছি, রেজাল্ট দেখে খুব খুশি।</p>
            <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>

        <!-- Review 13 -->
        <div class="review-card">
            <h4 class="customer-name">Joyanta Paul</h4>
            <p class="review-text">Midnapore, WB — খড়গপুর থেকে বলছি, ১০০% ট্রাস্টেড প্যানেল। কোনো ঝামেলা ছাড়াই অর্ডার কমপ্লিট হয়।</p>
            <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>

        <!-- Review 14 -->
        <div class="review-card">
            <h4 class="customer-name">Priya Kumari</h4>
            <p class="review-text">Ranchi, Jharkhand — Order complete hone me 2 ghante lage, drops ka issue abhi tak toh nahi aaya.</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

        <!-- Review 15 -->
        <div class="review-card">
            <h4 class="customer-name">Bikash Tudu</h4>
            <p class="review-text">Jhargram, WB — কম দামে কাজ হয়ে যায়, কোনো বড় কমপ্লেন নেই। ২ ঘণ্টার মধ্যেই ফলোয়ার্স এসে গিয়েছিল।</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

        <!-- Review 16 -->
        <div class="review-card">
            <h4 class="customer-name">Suresh Behera</h4>
            <p class="review-text">Cuttack, Odisha — Ketebale order late start hue, par complete hei jayee. Better server speed darsakar.</p>
            <div class="rating">⭐⭐</div>
        </div>

        <!-- Review 17 -->
        <div class="review-card">
            <h4 class="customer-name">Siddharth Verma</h4>
            <p class="review-text">Indore, MP — Cheap SMM panel for daily reseller orders. Instant delivery for most services.</p>
            <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>

        <!-- Review 18 -->
        <div class="review-card">
            <h4 class="customer-name">Taniya Mondal</h4>
            <p class="review-text">Nadia, WB — রানাঘাট থেকে বলছি, ইউটিউব ওয়াচটাইম ট্রাই করেছিলাম, মনিটাইজেশন অন হয়ে গেছে!</p>
            <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>

        <!-- Review 19 -->
        <div class="review-card">
            <h4 class="customer-name">Alok Kumar</h4>
            <p class="review-text">Gaya, Bihar — Non drop followers bole the, par 10-15 drop huye. Baad me refill service work kiya.</p>
            <div class="rating">⭐⭐⭐</div>
        </div>

        <!-- Review 20 -->
        <div class="review-card">
            <h4 class="customer-name">Sayani Mukherjee</h4>
            <p class="review-text">Kolkata, WB — দাম অনুযায়ী কাজের কোয়ালিটি বেশ ভালো। টুকটাক লেট হওয়া স্বাভাবিক, সমস্যা নেই।</p>
            <div class="rating">⭐⭐⭐⭐</div>
        </div>

    </div>

    <!-- Bottom Navigation Bar -->
    <?php include 'components/bottom-nav.php'; ?>

</body>
</html>
