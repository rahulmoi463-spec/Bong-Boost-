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
  <h4 class="customer-name">Priyanka Das</h4>
  <p class="review-text">Bankura, WB — বাঁকুড়া থেকে বলছি, আমার ইউটিউব চ্যানেলের ভিউস আর সাবস্ক্রাইবার খুব দ্রুত কমপ্লিট হয়ে গেছে! সত্যি দারুণ সার্ভিস, থ্যাঙ্ক ইউ Bong Boost!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rohan Sen</h4>
  <p class="review-text">Kolkata, WB — সার্ভিস অনেক ফাস্ট, তবে কাল সন্ধ্যায় কিউআর পেমেন্টে টাকা অ্যাড হতে ৫ মিনিট সময় লেগেছিল। বাকি সব ভালো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sowmya Nair</h4>
  <p class="review-text">Chennai, TN — Instagram reels views standard is good, but response from WhatsApp team was a bit late.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Amitabh Hazra</h4>
  <p class="review-text">Purulia, WB — পুরুলিয়া থেকে বলছি, ফেসবুক ওয়াচটাইম প্যাক কমপ্লিট হতে ৩ দিন লেগেছিল। সার্ভিস চালু থাকে কিন্তু গতি আরও বাড়ানো উচিত।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Shagufta Parveen</h4>
  <p class="review-text">Lucknow, UP — Telegram members service me kuch drop hua tha, par refill button dabate hi instant wapas aa gaya.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subhasis Ghosh</h4>
  <p class="review-text">Bardhaman, WB — কাল রাতে সার্ভার ডাউন থাকার জন্য একটা অর্ডার ক্যানসেল হয়েছিল, তবে টাকা অটো রিফান্ড হয়ে গেছে।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Aarti Gupta</h4>
  <p class="review-text">Delhi — Very low rates for Instagram likes! My online boutique post got very good engagement.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Vikram Yadav</h4>
  <p class="review-text">Patna, Bihar — UPI Auto add fund perfectly kaam karta hai. Panel ka interface bhi bohot simple hai.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Srabani Roy</h4>
  <p class="review-text">Howrah, WB — রিলস ভিউস ৫ সেকেন্ডে স্টার্ট হয়ে যায়, কিন্তু ফলোয়ার আসতে ১০-১৫ মিনিট দেরি হয়।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anitha Rajan</h4>
  <p class="review-text">Madurai, TN — Delivery was slightly delayed yesterday night, overall service quality is reliable.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Debabrata Bauri</h4>
  <p class="review-text">Asansol, WB — কম খরচে রিফুয়েল সার্ভিস দারুণ, তবে মাঝে মাঝে রিফিল বাটনে ২-৩ বার ক্লিক করতে হয়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Kavita Sharma</h4>
  <p class="review-text">Jaipur, Rajasthan — Followers quality mix thi, par rate ke hisab se service bilkul perfect hai.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mohd Bilal</h4>
  <p class="review-text">Varanasi, UP — Pehle 100 rupaye add karke dekha tha, kaam sahi hai par support system active hona chahiye 24/7.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Moumita Chakraborty</h4>
  <p class="review-text">Siliguri, WB — উত্তরবঙ্গ থেকে বলছি, পেজের লাইক প্রমোশন করিয়েছি, রেজাল্ট দেখে খুব খুশি।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Joyanta Paul</h4>
  <p class="review-text">Midnapore, WB — খড়গপুর থেকে বলছি, ১০০% ট্রাস্টেড প্যানেল। কোনো ঝামেলা ছাড়াই অর্ডার কমপ্লিট হয়।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Divya Menon</h4>
  <p class="review-text">Bengaluru — Order processing takes few minutes to show updated status, speed is acceptable.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sk Sameer</h4>
  <p class="review-text">Murshidabad, WB — সার্ভিস অনেক ফাস্ট, কিন্তু সার্ভারের নামগুলো আরেকটু সহজে লিখে দিলে কাস্টমারদের বুঝতে সুবিধা হতো।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Priya Kumari</h4>
  <p class="review-text">Ranchi, Jharkhand — Order complete hone me 2 ghante lage, drops ka issue abhi tak toh nahi aaya.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Bikash Tudu</h4>
  <p class="review-text">Jhargram, WB — কম দামে কাজ হয়ে যায়, কোনো বড় কমপ্লেন নেই। ২ ঘণ্টার মধ্যেই ফলোয়ার্স এসে গিয়েছিল।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Farhana Yasmin</h4>
  <p class="review-text">Kolkata, WB — Park Circus se hoon, Facebook page subscribers fast badhe hain. Satisfied service!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Suresh Behera</h4>
  <p class="review-text">Cuttack, Odisha — Ketebale order late start hue, par complete hei jayee. Better server speed darsakar.</p>
  <div class="rating">⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Siddharth Verma</h4>
  <p class="review-text">Indore, MP — Cheap SMM panel for daily reseller orders. Instant delivery for most services.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Taniya Mondal</h4>
  <p class="review-text">Nadia, WB — রানাঘাট থেকে বলছি, ইউটিউব ওয়াচটাইম ট্রাই করেছিলাম, মনিটাইজেশন অন হয়ে গেছে!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Alok Kumar</h4>
  <p class="review-text">Gaya, Bihar — Non drop followers bole the, par 10-15 drop huye. Baad me refill service work kiya.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ayesha Khatun</h4>
  <p class="review-text">Dhanbad, Jharkhand — Fast service delivery, WhatsApp contact response thoda fast hona chahiye.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anirban Dutta</h4>
  <p class="review-text">Hooghly, WB — লাইক পেতে কিছুটা দেরি হয়েছিল, তবে কাজ পুরোপুরি কমপ্লিট হয়েছে। ৪ স্টার দিলাম।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Karthik Pandian</h4>
  <p class="review-text">Coimbatore, TN — Good support team! Solved my payment deposit query within 10 minutes.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Mina Hembram</h4>
  <p class="review-text">Jhargram, WB — সার্ভিস অনেক ফাস্ট, তবে নতুনদের বোঝার জন্য সাইটে একটা হেল্প ভিডিও দিলে ভালো হতো।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rajesh Sharma</h4>
  <p class="review-text">Noida, UP — Good service quality. API order processing speed is smooth and hassle-free.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sayani Mukherjee</h4>
  <p class="review-text">Kolkata, WB — দাম অনুযায়ী কাজের কোয়ালিটি বেশ ভালো। টুকটাক লেট হওয়া স্বাভাবিক, সমস্যা নেই।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Manish Yadav</h4>
  <p class="review-text">Muzaffarpur, Bihar — Subscriptions order slow thaa par Support team ne guide kiya and issue resolve hua.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sarnali Ghosh</h4>
  <p class="review-text">Purulia, WB — আমার চ্যানেলের জন্য ১k সাবস্ক্রাইবার নিয়েছিলাম, ২ দিনে পারফেক্টভাবে কমপ্লিট হয়ে গেছে।</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Asif Ali</h4>
  <p class="review-text">Lucknow, UP — Sabse sasta panel hai bhai market me, bas auto refill button fast kaam karna chahiye.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Joydeb Bauri</h4>
  <p class="review-text">Bankura, WB — খাতড়া থেকে বলছি, সার্ভিস ভালো কিন্তু পেমেন্ট পেজে মাঝে মাঝেই স্লো দেখায়।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Neha Singh</h4>
  <p class="review-text">Guwahati, Assam — Cheap rates and quick response for reel views. Very satisfied overall.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Animesh Samanta</h4>
  <p class="review-text">Tamluk, WB — ইউটিউব ভিউস সার্ভিস পরশুদিন স্লো ছিল। তবে টেকনিক্যাল কোয়ালিটি নিয়ে কোনো কমপ্লেন নেই।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Meera Nair</h4>
  <p class="review-text">Kochi, Kerala — Fast processing after payment. Standard automated response dashboard.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rahul Malhotra</h4>
  <p class="review-text">Chandigarh — High speed server for Telegram votes. Minimum drops faced in last 30 days.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Moumita Sen</h4>
  <p class="review-text">Bankura, WB — ছাতনা থেকে ফেসবুক ফলোয়ার নিয়েছিলাম, ১ ঘণ্টার মধ্যে স্টার্ট হয়ে গিয়েছিল!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Rakesh Sahoo</h4>
  <p class="review-text">Bhubaneswar, Odisha — Order completed on time, but morning hours re WhatsApp reply late aasilhaa.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Pooja Verma</h4>
  <p class="review-text">Jaipur, Rajasthan — Overall good experience, Indian targeted followers non drop the.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Subodh Paul</h4>
  <p class="review-text">Malda, WB — মাঝে মাঝে পেজ রিলোড না করলে অর্ডার স্ট্যাটাস আপডেট দেখায় না, সিস্টেমটা ঠিক করা উচিত।</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Vijay Natarajan</h4>
  <p class="review-text">Chennai, TN — Affordable rates for Instagram followers. Order completion speed is consistent.</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Ritu Kumari</h4>
  <p class="review-text">Ranchi, Jharkhand — Server 2 order thoda slow tha kal, par balance auto return ho gaya.</p>
  <div class="rating">⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Tanmoy Sarkar</h4>
  <p class="review-text">Durgapur, WB — ওয়াচটাইম প্যাক ৪ দিনের জায়গায় ৫ দিন লেগেছে। তবে ফাইনালি ওয়াচটাইম যোগ হয়ে গেছে।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Simran Kaur</h4>
  <p class="review-text">Delhi — Instant processing and cheap server rates for reel viral services.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Amitabh Roy</h4>
  <p class="review-text">Cooch Behar, WB — টাকা কাটার পর সার্ভিস স্টার্ট হতে ২ ঘণ্টা টাইম লেগেছিল। সিস্টেম আরও ফাস্ট করুন।</p>
  <div class="rating">⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Anusha Reddy</h4>
  <p class="review-text">Hyderabad — Reliable service panel. Placed multiple orders for client social media accounts.</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Sujoy Hazra</h4>
  <p class="review-text">Birbhum, WB — সিউড়ি থেকে বলছি, প্রথমবার ট্রাই করলাম। কম খরচে ভালো কাজ পেয়েছি।</p>
  <div class="rating">⭐⭐⭐⭐</div>
</div>

<div class="review-card">
  <h4 class="customer-name">Shalini Mohanty</h4>
  <p class="review-text">Cuttack, Odisha — Fast speed execution, mo Youtube channel monetization pain bohut help helaa!</p>
  <div class="rating">⭐⭐⭐⭐⭐</div>
    </div>
      


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
