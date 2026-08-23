<style>
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #0f172a;
        border-top: 1px solid #334155;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
        z-index: 9999;
    }
    .bottom-nav a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 11px;
        text-align: center;
    }
    .bottom-nav a:hover, .bottom-nav a.active {
        color: #38bdf8;
    }
    .whatsapp-btn {
        background: #22c55e;
        color: #fff !important;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: bold;
    }
</style>
<a href="whatsapp-gift.php" class="nav-item">
    <div class="icon">🎁</div>
    <span>Free Gift</span>
</a>

<div class="bottom-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="faq.php">❓ FAQ</a>
    <a href="https://wa.me/917718231993?text=Hello,%20I%20need%20support" target="_blank" class="whatsapp-btn">💬 Support</a>
    <a href="calculator.php">🧮 Calc</a>
    <a href="terms.php">📜 Terms</a>
</div>
