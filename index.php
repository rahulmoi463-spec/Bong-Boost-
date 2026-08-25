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
  <h4 class="customer-name">Pooja Sharma</h4>
  <p class="review-text">Bankura, WB — বাঁকুড়া সদর থেকে বলছি, ইনস্টাগ্রামের রিলস ভিউস ও লাইক ২ মিনিটে ডেলিভারি হয়ে গেছে! ভীষণ হ্যাপি, থ্যাঙ্ক ইউ Bong Boost!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Arjun Das</h4>
  <p class="review-text">Purulia, WB — পুরুলিয়া থেকে বলছি, সার্ভিস খুব ভালো তবে কাল রাতে ফান্ড অ্যাড হতে ১৫ মিনিট দেরি হয়েছিল।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Neha Gupta</h4>
  <p class="review-text">Delhi — Order start hone me aadha ghanta laga tha, lekin followers quality sach me non-drop hai.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sowmya Krishnan</h4>
  <p class="review-text">Chennai, TN — Very fast delivery for YouTube views! Server speed was good today.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Debkumar Roy</h4>
  <p class="review-text">Bardhaman, WB — ওয়াচটাইম সার্ভিস চালু হতে একটু বেশি সময় নেয়, ১ দিনের জায়গায় ২ দিন লেগেছে। তবে কাজ হয়েছে।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Aisha Khatun</h4>
  <p class="review-text">Kolkata, WB — WhatsApp par support team ka reply thoda late aata hai shaam ko. Work quality aachi hai.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Tariq Khan</h4>
  <p class="review-text">Lucknow, UP — Telegram members service me kuch drop hua tha, par auto-refill button dabate hi wapas aa gaye.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sneha Chakraborty</h4>
  <p class="review-text">Midnapore, WB — খড়গপুর থেকে বলছি, রিলস ভিউসের স্পিড দারুণ, তবে নতুন কিছু পেমেন্ট অপশন দরকার।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subhadip Bauri</h4>
  <p class="review-text">Asansol, WB — দাম কম ঠিকই কিন্তু রাতে কাস্টমার কেয়ারের মেসেজের রিপ্লাই পেতে একটু বেশি অপেক্ষা করতে হয়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Priya Singh</h4>
  <p class="review-text">Ranchi, Jharkhand — Followers quality thik hai par order processing hone me thoda time lag gaya tha.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Raju Haldar</h4>
  <p class="review-text">Diamond Harbour, WB — অটোমেটিক কিউআর কোডে পেমেন্ট করতে গিয়ে আটকে গিয়েছিল, পরে সাপোর্ট হেল্প করেছে।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Karan Patel</h4>
  <p class="review-text">Ahmedabad, Gujarat — Smooth UI and very quick API processing. Best panel for resellers!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anitha Rajan</h4>
  <p class="review-text">Coimbatore, TN — Good service, but maximum limit for single order should be increased.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Taniya Dutta</h4>
  <p class="review-text">Hooghly, WB — লাইক পেতে কিছুটা দেরি হয়েছিল, তবে কাজ পুরোপুরি কমপ্লিট হয়েছে। ৪ স্টার দিলাম।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Suresh Behera</h4>
  <p class="review-text">Cuttack, Odisha — Bohut time re order cancel hei jayee. Re-order karibaku pade, taa chhada thik achi.</p>
  <div class="rating">⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Simran Kaur</h4>
  <p class="review-text">Chandigarh — Deposit issue solved in just 10 minutes by WhatsApp support. Friendly response.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sourav Sarkar</h4>
  <p class="review-text">Nadia, WB — ফেসবুক পেজের সাবস্ক্রাইবার স্পিড আরেকটু বাড়ানো দরকার। বাকি পরিষেবা ভালো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ritu Kumari</h4>
  <p class="review-text">Dhanbad, Jharkhand — Followers quality mixed hai, par kam price ke hisab se service worth hai.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anirban Mondal</h4>
  <p class="review-text">Kolkata, WB — সার্ভিস দুর্দান্ত, কিন্তু রাতে হোয়াটসঅ্যাপে অটো-রিপ্লাই বসালে কাস্টমারদের সুবিধা হতো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Saba Parveen</h4>
  <p class="review-text">Varanasi, UP — Order placed yesterday, start hone me poora 4 ghanta lag gaya. Thoda fast hona chahiye.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Bishal Tudu</h4>
  <p class="review-text">Jhargram, WB — ঝাড়গ্রাম থেকে অর্ডার করেছিলাম, কোনো বড় কমপ্লেন নেই। কাজ শেষ হতে ২ ঘণ্টা লেগেছিল।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Kavitha M.</h4>
  <p class="review-text">Madurai, TN — Slow start for Telegram votes service, but properly completed after few hours.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sujoy Hazra</h4>
  <p class="review-text">Birbhum, WB — প্রথমবার ট্রাই করলাম, ৫০ টাকা অ্যাড করেছিলাম। কোনো প্রবলেম ছাড়াই কাজ হয়ে গেছে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Manish Yadav</h4>
  <p class="review-text">Gaya, Bihar — UPI payment me 5 minute baad balance show hua. Pehle laga tha phans gaya.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rupa Paul</h4>
  <p class="review-text">Ranaghat, WB — আমার চ্যানেলের সাবস্ক্রাইবার খুব ফাস্ট বেড়েছে, তবে ২-৩টে অ্যাকাউন্ট ফেক লাগছিল।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sk Sameer</h4>
  <p class="review-text">Murshidabad, WB — সার্ভারের নামগুলো আরেকটু পরিষ্কারভাবে লিখলে কাস্টমারদের বুঝতে সুবিধা হতো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Divya Rajan</h4>
  <p class="review-text">Coimbatore, TN — Very good panel for beginner creators, prices are very low compared to others.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Chandan Sen</h4>
  <p class="review-text">Durgapur, WB — ওয়াচটাইম প্যাক নিয়েছিলাম, ৪ দিনের জায়গায় ৫ দিন সময় লেগেছে কমপ্লিট হতে। তবে কাজ হয়েছে!</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Amitabh Roy</h4>
  <p class="review-text">Cooch Behar, WB — টাকা কাটার পর সার্ভিস স্টার্ট হতে কেন জানি কালকে ৩ ঘণ্টা লেট হয়েছিল। সিস্টেম একটু ফাস্ট করুন।</p>
  <div class="rating">⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Farhana Yasmin</h4>
  <p class="review-text">Kolkata, WB — Facebook likes service was good. Delivery fast thi aur price bhi reasonable hai.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rakesh Sahoo</h4>
  <p class="review-text">Bhubaneswar, Odisha — Order completed on time, but WhatsApp support reply was late in morning hours.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Payel Das</h4>
  <p class="review-text">Barasat, WB — ইনস্টাগ্রাম রিলস ভিউস ৫ সেকেন্ডে স্টার্ট হয়ে যায়! কিন্তু লাইক আসতে ১০ মিনিট লেট হয়।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Alok Kumar</h4>
  <p class="review-text">Muzaffarpur, Bihar — Non drop followers bol ke diya tha, par 20-25 drop ho gaye. Refill ho gaya tha baad me.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sarnali Mukherjee</h4>
  <p class="review-text">Purulia, WB — আমার বুটিক পেজের প্রমোশনের জন্য খুব হেল্পফুল। রেট অন্য জায়গার চেয়ে বেশ কম।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mohd Tariq</h4>
  <p class="review-text">Bareilly, UP — SMM reseller panel me ye best hai, lekin UI design thoda aur modern banao.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Bappaditya Kar</h4>
  <p class="review-text">Bankura, WB — খাতড়া থেকে অর্ডার করেছিলাম। সার্ভিস ভালো কিন্তু পেমেন্ট পেজে মাঝে মাঝেই স্লো দেখায়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Priyanka Sen</h4>
  <p class="review-text">Guwahati, Assam — Cheap rates for reel views. Smooth experience overall for daily reseller work.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Animesh Samanta</h4>
  <p class="review-text">Tamluk, WB — ইউটিউব ভিউস সার্ভিস খুব স্লো চলছিল পরশুদিন। তবে কাজের কোয়ালিটি নিয়ে কোনো সমস্যা নেই।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Siddharth Rao</h4>
  <p class="review-text">Bengaluru — Automatic API response is quick. Minimum order drop issues faced so far.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mina Besra</h4>
  <p class="review-text">Jhargram, WB — সার্ভিস অনেক ফাস্ট, তবে নতুনদের কাজ বোঝার জন্য সাইটে একটি টিউটোরিয়াল ভিডিও দেওয়া উচিত।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rahul Sharma</h4>
  <p class="review-text">Indore, MP — Service speed is fine, but WhatsApp chat option should be kept active 24x7.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sayani Chakraborty</h4>
  <p class="review-text">Kolkata, WB — ভালোই সার্ভিস পেলাম, দামও বাজেট ফ্রেন্ডলি। পিক আওয়ারে সামান্য দেরি হওয়া স্বাভাবিক।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Asif Ali</h4>
  <p class="review-text">Dhanbad, Jharkhand — Best SMM panel for low budget creators, but please speed up auto-refill button.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Joydeb Ghosh</h4>
  <p class="review-text">Suri, WB — সিউড়ি থেকে বলছি, কাজ তো পারফেক্ট হয় কিন্তু সার্ভারের গতি আরেকটু বাড়ালে ভালো হয়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anusha Reddy</h4>
  <p class="review-text">Hyderabad — Reliable service provider. Ordered Instagram likes multiple times without any issue.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subodh Paul</h4>
  <p class="review-text">Malda, WB — সার্ভিস ভালো কিন্তু মাঝে মাঝে পেজ রিলোড না করলে অর্ডার স্ট্যাটাস আপডেট দেখায় না।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Meera Nair</h4>
  <p class="review-text">Kochi, Kerala — Fast processing after fund deposit. Standard quality social media marketing panel.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Vikram Malhotra</h4>
  <p class="review-text">Noida, UP — Telegram members stay intact. Minimum decrease observed in 30 days.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Moumita Bauri</h4>
  <p class="review-text">Bankura, WB — ছাতনা থেকে ফেসবুক ফলোয়ার নিয়েছিলাম, ২ ঘণ্টার মধ্যে পুরো ১০০% স্টার্ট হয়ে গিয়েছিল!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Karthik Pandian</h4>
  <p class="review-text">Chennai, TN — Good service response and automated refund system for failed orders.</p>
  <div class="rating">⭐⭐⭐⭐</div>
    </div>
    


        <div class="review-card">
  <h4 class="customer-name">Arjun Ghosh</h4>
  <p class="review-text">Bankura, WB — সার্ভিস সব ঠিকঠাক আছে, তবে কাল রাতে সার্ভার একটু স্লো ছিল। ৩ ঘণ্টা পর অর্ডার স্টার্ট হয়েছিল। বাকি অল ওকে!</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Pooja Sharma</h4>
  <p class="review-text">Delhi — Order start hone me aadha ghanta laga, but quality achhi hai. Drops bilkul nahi huye.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Debkumar Roy</h4>
  <p class="review-text">Bardhaman, WB — ভালো প্যানেল কিন্তু ওয়াচটাইম সার্ভিস চালু হতে একটু বেশি সময় নেয়। এটা ফাস্ট করলে আরও ভালো হয়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Aisha Khatun</h4>
  <p class="review-text">Kolkata, WB — WhatsApp e reply thoda late milta hai shaam ko. Work quality acchi hai.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sanjay Mahato</h4>
  <p class="review-text">Purulia, WB — ১k ফলোয়ারের মধ্যে ১০-১৫টা ড্রপ করেছিল, যদিও রিফিল বাটনে ক্লিক করতেই আবার দিয়ে দিয়েছে।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Vikram Singh</h4>
  <p class="review-text">Patna, Bihar — Server maintenance ke time fund add late hua tha. Ticket create karne ke baad 1 hour me add hua.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Srabani Das</h4>
  <p class="review-text">Howrah, WB — ইনস্ট্যান্ট সার্ভিস হলেও মাঝে মাঝে কিছু সার্ভিস ক্যানসেল হয়ে টাকা রিফান্ড হয়ে যায়। সিস্টেমটা ঠিক করা দরকার।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mohd Imran</h4>
  <p class="review-text">Lucknow, UP — Bhai rate toh bohot cheap hai lekin Telegram server kabhi kabhi slow chalta hai.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sneha Chakraborty</h4>
  <p class="review-text">Midnapore, WB — রিলস ভিউসের স্পিড খুব ভালো, তবে আরও কিছু নতুন পেমেন্ট অপশন যোগ করলে ভালো হতো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anitha Krishnan</h4>
  <p class="review-text">Chennai, TN — Good site, but maximum limit per order for YouTube subscribers should be increased.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subhadip Bauri</h4>
  <p class="review-text">Asansol, WB — সার্ভিসের দাম কম ঠিকই, কিন্তু রাতে সাপোর্ট টিমের রিপ্লাই পেতে একটু বেশি অপেক্ষা করতে হয়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Priya Gupta</h4>
  <p class="review-text">Ranchi, Jharkhand — Follower quality acchi hai par processing hone me thoda time lag gaya tha mera.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Raju Haldar</h4>
  <p class="review-text">Diamond Harbour, WB — অটোমেটিক টাকা অ্যাড করতে গিয়ে একবার কিউআর কোড আটকে গিয়েছিল, পরে হোয়াটসঅ্যাপে মেসেজ করার পর অ্যাড করে দিয়েছে।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Deepak Verma</h4>
  <p class="review-text">Jaipur, Rajasthan — Views were instant, but 5-10% dropped after 2 weeks. Refill process took 24 hours.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Moumita Roy</h4>
  <p class="review-text">Siliguri, WB — কাজের কোয়ালিটি দারুণ। তবে সার্ভারের কাজের সময় ওয়েবসাইট একটু স্লো কাজ করে।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Karan Patel</h4>
  <p class="review-text">Ahmedabad, Gujarat — SMM panel speed is decent. Cheap rates for Insta likes.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Taniya Dutta</h4>
  <p class="review-text">Hooghly, WB — লাইক পেতে কিছুটা দেরি হয়েছিল, তবে রেজাল্ট পেয়েছি। ৫ স্টারের বদলে ৪ স্টার দিলাম।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Suresh Behera</h4>
  <p class="review-text">Cuttack, Odisha — Bohut time re order cancel hei jayee. Re-order karibaku pade. Baaki sab thik achi.</p>
  <div class="rating">⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Simran Kaur</h4>
  <p class="review-text">Chandigarh — Customer support is polite, fixed my deposit error in 10 minutes.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sourav Sarkar</h4>
  <p class="review-text">Nadia, WB — ফেসবুক পেজের সাবস্ক্রাইবার স্পিড আরেকটু বাড়ানো দরকার। বাকি পরিষেবা ভালো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ritu Kumari</h4>
  <p class="review-text">Dhanbad, Jharkhand — Followers quality mixed hai, kuch profile me DP nahi tha. Price ke hisab se thik hi hai.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anirban Mondal</h4>
  <p class="review-text">Kolkata, WB — সার্ভিস দুর্দান্ত, কিন্তু রাতে হোয়াটসঅ্যাপে অটো-রিপ্লাই দিলে সুবিধা হতো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Saba Parveen</h4>
  <p class="review-text">Varanasi, UP — Order placed yesterday, complete hone me poora 1 din lag gaya. Thoda fast hona chahiye.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Bishal Tudu</h4>
  <p class="review-text">Jhargram, WB — কম খরচে ভালো জিনিস, কোনো বড় কমপ্লেন নেই। কাজ শেষ হতে ২ ঘণ্টা লেগেছিল।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Kavitha M.</h4>
  <p class="review-text">Coimbatore, TN — Slow start for Telegram votes service, but completed 100% properly.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sujoy Hazra</h4>
  <p class="review-text">Birbhum, WB — প্রথমবার ট্রাই করলাম, ৫০ টাকা অ্যাড করেছিলাম। কোনো প্রবলেম ছাড়াই কাজ হয়ে গেছে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Manish Yadav</h4>
  <p class="review-text">Gaya, Bihar — UPI se payment karne par 5 minute bad balance show hua. Thoda panic ho gaya tha main.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rupa Paul</h4>
  <p class="review-text">Ranaghat, WB — আমার চ্যানেলের সাবস্ক্রাইবার খুব ফাস্ট বেড়েছে, তবে কয়েকটা অ্যাকাউন্ট ফেক লাগছিল।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sk Sameer</h4>
  <p class="review-text">Murshidabad, WB — সার্ভারের নামগুলো আরেকটু পরিষ্কারভাবে লিখলে সুবিধা হতো বুঝতে কোনটা ভালো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Neha Sharma</h4>
  <p class="review-text">Noida, UP — Awesome panel! Cheap rates and easy interface. Highly satisfied.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Tanmoy Ghosh</h4>
  <p class="review-text">Malda, WB — সার্ভিস অনেক দ্রুত কাজ করে, তবে মাঝে মাঝে রিফিল স্পিড একটু স্লো হয়ে যায়।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Divya Rajan</h4>
  <p class="review-text">Madurai, TN — Very good panel for beginner creators, prices are very low.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Chandan Sen</h4>
  <p class="review-text">Durgapur, WB — ওয়াচটাইম প্যাক নিয়েছিলাম, ৪ দিনের জায়গায় ৬ দিন সময় লেগেছে কমপ্লিট হতে। কিন্তু মনিটাইজ হয়ে গেছে!</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Amitabh Roy</h4>
  <p class="review-text">Cooch Behar, WB — টাকা কাটার পর সার্ভিস স্টার্ট হতে কেন জানি ২ ঘণ্টা লেট হয়েছিল কালকে। প্যানেল আপডেট করা দরকার।</p>
  <div class="rating">⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Farhana Yasmin</h4>
  <p class="review-text">Kolkata, WB — Facebook likes service was good. Delivery fast thi.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rakesh Sahoo</h4>
  <p class="review-text">Bhubaneswar, Odisha — Order completed on time, but WhatsApp support reply was late in morning.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Payel Das</h4>
  <p class="review-text">Barasat, WB — ইনস্টাগ্রাম রিলস ভিউস ৫ সেকেন্ডে স্টার্ট হয়ে যায়! কিন্তু লাইক আসতে ১০ মিনিট লেট হয়।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Alok Kumar</h4>
  <p class="review-text">Muzaffarpur, Bihar — Non drop followers bol ke diya tha, par 20-25 drop ho gaye. Though refill ho gaya.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sarnali Mukherjee</h4>
  <p class="review-text">Purulia, WB — আমার বুটিক পেজের জন্য খুব হেল্পফুল। রেট অন্য জায়গার চেয়ে বেশ কম।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mohd Tariq</h4>
  <p class="review-text">Bareilly, UP — SMM reseller panel me ye best hai, lekin UI thoda aur modern banao.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Bappaditya Kar</h4>
  <p class="review-text">Bankura, WB — খাতড়া থেকে অর্ডার করেছিলাম। সার্ভিস ভালো কিন্তু পেমেন্ট পেজে মাঝে মাঝেই সার্ভার ডাউন দেখায়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Priyanka Sen</h4>
  <p class="review-text">Guwahati, Assam — Cheap rates for reel views. Smooth experience overall.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Animesh Samanta</h4>
  <p class="review-text">Tamluk, WB — ইউটিউব ভিউস সার্ভিস খুব স্লো চলছিল পরশুদিন। তবে কাজের কোয়ালিটি নিয়ে কোনো সন্দেহ নেই।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Siddharth Rao</h4>
  <p class="review-text">Bengaluru — Automatic API integration is smooth. Very minimal order drops.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mina Besra</h4>
  <p class="review-text">Jhargram, WB — সার্ভিস অনেক ফাস্ট, তবে নতুনদের বোঝার জন্য সাইটে একটি ভিডিও টিউটোরিয়াল দিলে ভালো হতো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rahul Sharma</h4>
  <p class="review-text">Indore, MP — Service speed is fine, but WhatsApp chat option should be 24x7 live.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sayani Chakraborty</h4>
  <p class="review-text">Kolkata, WB — ভালোই সার্ভিস পেলাম, দামও পকেটের মধ্যে। টুকটাক দেরি হওয়া স্বাভাবিক।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Asif Ali</h4>
  <p class="review-text">Dhanbad, Jharkhand — Best SMM panel for low budget creators, but please speed up auto-refill button.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Joydeb Ghosh</h4>
  <p class="review-text">Suri, WB — সিউড়ি থেকে বলছি, কাজ তো পারফেক্ট হয় কিন্তু সার্ভারের কিছু কাজ একটু ফাস্ট হওয়া উচিত।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anusha Reddy</h4>
  <p class="review-text">Hyderabad — Reliable service provider. Ordered Instagram likes multiple times without issue.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
        </div>
    



<div class="review-card">
  <h4 class="customer-name">Pinaki Mukherjee</h4>
  <p class="review-text">Bankura, WB — বাঁকুড়া সদর থেকে বলছি, রিলস ভিউস এত ফাস্ট অ্যাড হবে ভাবতেই পারিনি!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sameer Verma</h4>
  <p class="review-text">Delhi — Bhai Insta followers service ekdum instant hai, 2 min me start ho gaya.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Arun Kumar</h4>
  <p class="review-text">Chennai, TN — Very reliable SMM panel! Telegram members service was fast and non-drop.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Soumya Ranjan</h4>
  <p class="review-text">Cuttack, Odisha — Bhalaa service, automatic order complete heiparla. Bohut dhanyabad!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Tanmoy Ghosh</h4>
  <p class="review-text">Bardhaman, WB — মেমারি থেকে বলছি, ফেসবুক পেজ লাইকের জন্য ১ নম্বর প্যানেল।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Vikram Singh</h4>
  <p class="review-text">Patna, Bihar — UPI QR code se payment instantly add ho jata hai, zero technical issue.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anitha Rajan</h4>
  <p class="review-text">Coimbatore, TN — Super speed delivery for YouTube views. Very smooth experience.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Pratiksha Sen</h4>
  <p class="review-text">Kolkata, WB — Salt Lake area, YouTube Watch Time package khub bhalo bhabe complete holo.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rohan Sharma</h4>
  <p class="review-text">Lucknow, UP — SMM reseller ke liye ye panel sabse best rates deta hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Dibyendu Das</h4>
  <p class="review-text">Purulia, WB — পুরুলিয়া আরসা থেকে বলছি, সাবস্ক্রাইবার রেট সবচেয়ে কম এই ওয়েবসাইটে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Manoj Mohanty</h4>
  <p class="review-text">Bhubaneswar, Odisha — Khub bhala panel. Instant support ebong fast execution.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Siddharth Menon</h4>
  <p class="review-text">Bengaluru — Clean dashboard UI and very automated order tracking system.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Amitabh Hazra</h4>
  <p class="review-text">Midnapore, WB — খড়গপুর থেকে বলছি, অটো-রিফিল ফিচারটা দারুণ কাজ করে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Manish Yadav</h4>
  <p class="review-text">Ranchi, Jharkhand — Reel viral karne ke liye best service. Views rate 1 no. hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Karthik Pandian</h4>
  <p class="review-text">Madurai, TN — Romba nalla service! Followers count drop aagala, full trustable.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Joyanta Biswas</h4>
  <p class="review-text">Ranaghat, WB — রানাঘাট থেকে বলছি, হোয়াটসঅ্যাপ সাপোর্ট টিম খুব তাড়াতাড়ি রিপ্লাই দেয়।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Suraj Gupta</h4>
  <p class="review-text">Guwahati, Assam — Order place karte hi 5 minute me complete ho gaya. Awesome!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">David L.</h4>
  <p class="review-text">Mumbai — Recommended by a creator friend. Truly 100% genuine followers provided.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Biswajit Sahoo</h4>
  <p class="review-text">Bhadrak, Odisha — Aapekhyaa tharu bohut fast service milila. Dhanyabad team!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sk Imran</h4>
  <p class="review-text">Diamond Harbour, WB — ডায়মন্ড হারবার থেকে পেজ প্রমোশন করিয়েছিলাম, ১ দিনেই ১০k কমপ্লিট!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Pankaj Mishra</h4>
  <p class="review-text">Varanasi, UP — Pehle 50 rupaye add karke try kiya tha, kaam dekh kar dil khush ho gaya.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Suresh Kumar</h4>
  <p class="review-text">Salem, TN — Very fast customer service response via WhatsApp support.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subhadip Samanta</h4>
  <p class="review-text">Tamluk, WB — তমলুক থেকে রেগুলার রিসেল করি, কোনো কাস্টমারের থেকে কমপ্লেন আসেনি।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Deepak Agarwal</h4>
  <p class="review-text">Jaipur, Rajasthan — Genuine Indian target followers quality is really impressive.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Satyajit Behera</h4>
  <p class="review-text">Puri, Odisha — Odisha re ettiki sasta ebong fast panel au nahi.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Aritra Dutta</h4>
  <p class="review-text">Howrah, WB — হাওড়া ময়দান থেকে বলছি, সার্ভিস কোয়ালিটি আর রেট একদম পারফেক্ট।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rajesh Sharma</h4>
  <p class="review-text">Indore, MP — SMM panel market me sabse cheap and fast server response.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Vijay Natarajan</h4>
  <p class="review-text">Chennai, TN — Best price for Instagram likes and views. Excellent site speed.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ratul Bauri</h4>
  <p class="review-text">Asansol, WB — আসানসোল থেকে ১k সাবস্ক্রাইবার নিয়েছিলাম, মনিটাইজেশন অন হয়ে গেছে!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ashok Kumar</h4>
  <p class="review-text">Dehradun, UK — Best experience! Auto refill system works smoothly without any issue.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>


        <div class="review-card">
  <h4 class="customer-name">Pooja Sharma</h4>
  <p class="review-text">Bankura, WB — বাঁকুড়া থেকে রিলস বানাই, ফলোয়ার বাড়ানোর জন্য সেরা প্যানেল। খুব থ্যাঙ্ক ইউ!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Neha Gupta</h4>
  <p class="review-text">Delhi — Instagram likes service is super fast! My boutique page post engagement boosted naturally.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Tariq Khan</h4>
  <p class="review-text">Lucknow, UP — Bohat umdah service hai, Telegram members bilkul instant join ho gaye.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sowmya Krishnan</h4>
  <p class="review-text">Chennai, TN — Semma service! YouTube subscribers count drop aagala, very genuine site.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sneha Mondal</h4>
  <p class="review-text">Bardhaman, WB — বর্ধমান থেকে বিউটি ব্লগিং করি, ভিউস সার্ভিস নিয়ে খুব ভালো রেজাল্ট পেয়েছি।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ayesha Siddiqua</h4>
  <p class="review-text">Hyderabad — Fast customer support response and hassle-free UPI add fund system.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anjali Roy</h4>
  <p class="review-text">Kolkata, WB — Salt Lake se hu. Facebook page monetization Watch Time package properly complete ho gaya!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mohd Bilal</h4>
  <p class="review-text">Patna, Bihar — Bohat acchi site hai bhai, rate pure market me sabse kam hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anusha Reddy</h4>
  <p class="review-text">Bengaluru — Very clean UI and transparent order status tracking system. Loved it!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ritu Bauri</h4>
  <p class="review-text">Purulia, WB — পুরুলিয়া থেকে কুকিং চ্যানেল চালাই, ১k সাবস্ক্রাইবার খুব ফাস্ট কমপ্লিট করে দিয়েছে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Divya Swaminathan</h4>
  <p class="review-text">Coimbatore, TN — Very fast delivery for Instagram reels views. Highly recommended!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sayantani Das</h4>
  <p class="review-text">Howrah, WB — হাওড়া ময়দান থেকে বলছি, সাপোর্ট টিমের কথা বলার ধরণ খুব সুন্দর ও হেল্পফুল।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Priyanka Kumari</h4>
  <p class="review-text">Ranchi, Jharkhand — Daily orders lagati hu client ke liye, auto-refill hamesha work karta hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Shagufta Yasmin</h4>
  <p class="review-text">Kolkata, WB — Park Circus se hoon, SMM panel ki speed aur security dono 1 number hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Shalini Mohanty</h4>
  <p class="review-text">Bhubaneswar, Odisha — Mo channel re views instantly badhigala. Bohut bhala lagila service!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Moumita Ghosh</h4>
  <p class="review-text">Siliguri, WB — উত্তরবঙ্গ থেকে অনলাইন শপ চালাই, পেজ ফলোয়ার নিয়ে পেজের ট্রাস্ট অনেক বেড়েছে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Kavitha Raj</h4>
  <p class="review-text">Madurai, TN — Nalla quality followers. Instant processing time after placing order.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Swati Pandey</h4>
  <p class="review-text">Varanasi, UP — Pehle dar lag raha tha, par order instant start ho gaya. Very reliable!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Payel Dutta</h4>
  <p class="review-text">Medinipur, WB — মেদিনীপুর সদর থেকে বলছি, এত কম টাকায় রিলস ভিউস ভাবাই যায় না!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Saba Parveen</h4>
  <p class="review-text">Dhanbad, Jharkhand — Bohat zabardast panel hai, fund auto add ho jata hai bina kisi issue ke.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rupa Chakraborty</h4>
  <p class="review-text">Asansol, WB — আসানসোল থেকে বুটিকের ফেসবুক লাইভ করি, লাইক ভিউস সার্ভিস খুব ভালো পেয়েছি।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rashmi Verma</h4>
  <p class="review-text">Jaipur, Rajasthan — Best experience for Instagram page promotion. Guaranteed quality!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Monika Sen</h4>
  <p class="review-text">Malda, WB — মালদা থেকে পেজের জন্য ফলোয়ার নিয়েছিলাম, একটাও ডিক্রিজ হয়নি।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Meera Nair</h4>
  <p class="review-text">Kochi, Kerala — Very smooth transaction and automated service response. Loved it.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Bhavna Joshi</h4>
  <p class="review-text">Ahmedabad, Gujarat — Affordable prices and instant start for all social media services.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Taniya Paul</h4>
  <p class="review-text">Nadia, WB — কৃষ্ণনগর থেকে ডান্স রিলস পোস্ট করি, ভিউস স্পিড দেখে জাস্ট অবাক হয়ে গেছি!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sonali Sahoo</h4>
  <p class="review-text">Cuttack, Odisha — Bohut fast service milila, mo Insta account growth bohut bhala helaa.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ishita Chatterjee</h4>
  <p class="review-text">Hooghly, WB — শ্রীরামপুর থেকে বলছি, নিজের প্যানেল বানিয়ে নেওয়ার চেয়ে এখান থেকে রিসেল করা অনেক লাভজনক!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Kiran Mazumdar</h4>
  <p class="review-text">Guwahati, Assam — Order placed and start within 2 minutes. Awesome panel!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mina Hembram</h4>
  <p class="review-text">Jhargram, WB — ঝাড়গ্রাম থেকে ইউটিউব চ্যানেলের জন্য কাজ করিয়েছি, পুরো ১০০% জেনুইন।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
    </div>
                                                                                                              

        <div class="review-card">
  <h4 class="customer-name">Rahul Moi</h4>
  <p class="review-text">Bankura, WB — এত সস্তায় এবং দ্রুত ইনস্টাগ্রাম ফলোয়ার বাড়বে ভাবতেই পারিনি! সাপোর্ট টিম খুব হেল্পফুল।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sanjay Mahato</h4>
  <p class="review-text">Purulia, WB — পুরুলিয়া থেকে অর্ডার করেছিলাম। মাত্র ১০ মিনিটে আমার ইউটিউব ভিউজ কমপ্লিট হয়ে গেছে। সেরা প্যানেল!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Amitava Roy</h4>
  <p class="review-text">Bardhaman, WB — Super fast service! Bardhaman er moddhe eto valo SMM panel r nei. Fully trusted!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Priya Chakraborty</h4>
  <p class="review-text">Kolkata, WB — Kolkata re-seller service. Auto-refill button really works great. Highly recommended!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subodh Ghosh</h4>
  <p class="review-text">Paschim Medinipur, WB — মেদিনীপুর শহর থেকে রেগুলার অর্ডার করি। কোনদিন একটা অর্ডারও ড্রপ হয়নি।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Suman Adhikary</h4>
  <p class="review-text">Purba Medinipur, WB — তমলুক থেকে কাস্টমার সার্ভিস দারুণ পেয়েছি। ফান্ড এড হতে ২ মিনিট সময় লেগেছিল।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rakib Haldar</h4>
  <p class="review-text">Diamond Harbour, WB — ডায়মন্ড হারবার থেকে পেজ প্রমোশন করিয়েছিলাম। ২ দিনে ১০k লাইক পেয়ে গেছি!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Deepak Shaw</h4>
  <p class="review-text">Howrah, WB — Very instant delivery for Telegram members. Best prices compared to market. 100% genuine.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Animesh Samanta</h4>
  <p class="review-text">Hooghly, WB — শ্রীরামপুর থেকে বলছি, অটোমেটিক এপিআই প্রসেসিং এত ফাস্ট যে অর্ডার সাথে সাথে স্টার্ট হয়ে যায়।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rohit Sharma</h4>
  <p class="review-text">Siliguri, WB — Siliguri se hoon, pehle darr lag raha tha par service dekh ke maza aa gaya. Best panel!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Tanmoy Saha</h4>
  <p class="review-text">Malda, WB — মালদা থেকে বলছি, অন্যান্য প্যানেল ইউজ করেছি কিন্তু এই প্যানেলের মতো চিপ রেটে কোয়ালিটি কোথাও নেই।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sourav Das</h4>
  <p class="review-text">Birbhum, WB — বোলপুর শান্তিনিকেতন থেকে অর্ডার করি। ইউটিউব ওয়াচটাইম ১ সপ্তাহের মধ্যে কমপ্লিট করে দিয়েছে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Bishal Pal</h4>
  <p class="review-text">Nadia, WB — কৃষ্ণনগর থেকে বলছি, একদম রিয়েল ইন্ডিয়ান কাস্টমার ফলোয়ার পেয়েছি। কোন ডিক্রিজ নেই।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sayani Dutta</h4>
  <p class="review-text">Barasat, WB — Barasat er moddhe sobcheye trusted site. UPI payment instant add hoe jay. Khub bhalo laglo.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Imran Sarkar</h4>
  <p class="review-text">Murshidabad, WB — বহরমপুর থেকে বলছি, রিলস ভিউসের রেট এত কম যে অবিশ্বাস্য! সবাই ট্রাই করতে পারো।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Vikram Malhotra</h4>
  <p class="review-text">Delhi — Best SMM Panel in India! Very cheap rates for Instagram Likes and views. Fast server speed.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Karan Mehta</h4>
  <p class="review-text">Mumbai — Bahut badiya service hai bhai. Instant start ho jata hai order. Support team 24/7 active hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Alok Kumar</h4>
  <p class="review-text">Patna, Bihar — Patna se bol raha hu. Subscriber service bohot fast aur non-drop hai. Fully satisfied!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Manish Tirkey</h4>
  <p class="review-text">Ranchi, Jharkhand — Ranchi se daily orders lagata hu. Resellers ke liye ye panel sabse best price deta hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ankur Baruah</h4>
  <p class="review-text">Guwahati, Assam — Guwahati se order diya tha, 5 minute me start ho gaya. Very trustworthy SMM panel.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subham Pattnaik</h4>
  <p class="review-text">Bhubaneswar, Odisha — Great experience! Smooth UI and quick auto-refill feature. Highly recommend!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Shivam Verma</h4>
  <p class="review-text">Lucknow, UP — Ek no. panel hai bhai! UPI QR code se payment 1 second me add ho jata hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Siddharth Rao</h4>
  <p class="review-text">Bengaluru — Clean UI, smooth speed and automated API response. Best SMM provider from WB!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Prabin Burman</h4>
  <p class="review-text">Cooch Behar, WB — কোচবিহার থেকে বলছি, সার্ভিস নিয়ে কোনো অভিযোগ নেই। সবসময় ১ নাম্বার কোয়ালিটি দেয়।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Kalyan Roy</h4>
  <p class="review-text">Jalpaiguri, WB — জলপাইগুড়ি থেকে পেজের জন্য ফলোয়ার নিয়েছিলাম। খুব সুন্দরভাবে কাজ কমপ্লিট হয়েছে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
    </div>
                                                                                                                             

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
