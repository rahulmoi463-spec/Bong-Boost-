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
<!-- Bong Boost Background Music Player Start -->
<audio id="bgMusic" loop preload="none">
    <source src="https://stream.zeno.fm/f3wvbbqmdg8uv" type="audio/mpeg">
</audio>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var audio = document.getElementById("bgMusic");

        // থ্রি-লাইন স্লাইডবার বা নেভিগেশন মেনু খুঁজে বের করা
        var navMenu = document.querySelector(".offcanvas-body") || document.querySelector(".nav-links") || document.querySelector(".menu-items") || document.querySelector("nav ul");

        if (!navMenu) {
            var firstLink = document.querySelector("a[href*='logout']") || document.querySelector("a[href*='order']");
            if (firstLink && firstLink.parentElement) {
                navMenu = firstLink.parentElement.parentElement || firstLink.parentElement;
            } else {
                navMenu = document.body;
            }
        }

        // মেনু আইটেম বাটন তৈরি
        var musicMenuItem = document.createElement("div");
        musicMenuItem.style.cssText = "padding: 12px 20px; cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 500; font-size: 15px; color: #fff; transition: 0.3s;";
        musicMenuItem.id = "musicToggleBtn";
        musicMenuItem.innerHTML = '🎵 <span>Background Music: <b style="color: #28a745;">OFF</b></span>';

        navMenu.appendChild(musicMenuItem);

        // প্রথম স্ক্রিন ক্লিকে অটো-প্লে
        function enableAutoplay() {
            if (audio.paused) {
                audio.play().then(function() {
                    musicMenuItem.innerHTML = '🎵 <span>Background Music: <b style="color: #dc3545;">ON</b></span>';
                }).catch(function(error) {
                    console.log("Autoplay blocked");
                });
            }
            document.removeEventListener("click", enableAutoplay);
        }
        document.addEventListener("click", enableAutoplay, { once: true });

        // বাটন টগল হ্যান্ডলার
        musicMenuItem.addEventListener("click", function (e) {
            e.stopPropagation();
            if (audio.paused) {
                audio.play();
                musicMenuItem.innerHTML = '🎵 <span>Background Music: <b style="color: #dc3545;">ON</b></span>';
            } else {
                audio.pause();
                musicMenuItem.innerHTML = '🎵 <span>Background Music: <b style="color: #28a745;">OFF</b></span>';
            }
        });
    });
</script>
<!-- Bong Boost Background Music Player End -->
