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
<audio id="bgMusic" loop preload="auto">
    <source src="https://stream.zeno.fm/f3wvbbqmdg8uv" type="audio/mpeg">
</audio>

<div id="musicControlWidget" style="position: fixed; bottom: 70px; right: 15px; z-index: 99999; display: flex; align-items: center; gap: 8px; background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); padding: 6px 12px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.4);">
    
    <div id="musicWave" style="display: flex; align-items: flex-end; gap: 2px; height: 14px;">
        <span class="bar" style="width: 3px; height: 100%; background: #38bdf8; border-radius: 2px;"></span>
        <span class="bar" style="width: 3px; height: 60%; background: #38bdf8; border-radius: 2px;"></span>
        <span class="bar" style="width: 3px; height: 80%; background: #38bdf8; border-radius: 2px;"></span>
    </div>

    <button id="musicToggleBtn" style="background: #0284c7; border: none; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; outline: none; transition: 0.2s;">
        <span id="playIcon" style="display: none; font-size: 14px; margin-left: 2px;">▶</span>
        <span id="pauseIcon" style="display: inline; font-size: 12px;">⏸</span>
    </button>
    
    <span id="musicStatusText" style="color: #f8fafc; font-size: 12px; font-weight: 600; font-family: sans-serif;">Background Music</span>
</div>

<style>
    @keyframes pulseWave {
        0%, 100% { height: 40%; }
        50% { height: 100%; }
    }
    .playing .bar:nth-child(1) { animation: pulseWave 0.8s infinite ease-in-out; }
    .playing .bar:nth-child(2) { animation: pulseWave 0.6s infinite ease-in-out 0.2s; }
    .playing .bar:nth-child(3) { animation: pulseWave 0.9s infinite ease-in-out 0.4s; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var audio = document.getElementById("bgMusic");
        var btn = document.getElementById("musicToggleBtn");
        var playIcon = document.getElementById("playIcon");
        var pauseIcon = document.getElementById("pauseIcon");
        var wave = document.getElementById("musicWave");

        function updateUI(isPlaying) {
            if (isPlaying) {
                playIcon.style.display = "none";
                pauseIcon.style.display = "inline";
                btn.style.background = "#ef4444"; // পজ করার জন্য লাল বাটন
                wave.classList.add("playing");
            } else {
                playIcon.style.display = "inline";
                pauseIcon.style.display = "none";
                btn.style.background = "#10b981"; // প্লে করার জন্য সবুজ বাটন
                wave.classList.remove("playing");
            }
        }

        // অটো প্লে করার চেষ্টা
        function tryAutoPlay() {
            var promise = audio.play();
            if (promise !== undefined) {
                promise.then(function() {
                    updateUI(true);
                }).catch(function(error) {
                    // ব্রাউজার যদি স্ক্রিন টাচ ছাড়া প্লে ব্লক করে
                    updateUI(false);
                    var enableAudioOnTouch = function() {
                        audio.play().then(function() {
                            updateUI(true);
                        });
                        document.removeEventListener("click", enableAudioOnTouch);
                        document.removeEventListener("touchstart", enableAudioOnTouch);
                    };
                    document.addEventListener("click", enableAudioOnTouch, { once: true });
                    document.addEventListener("touchstart", enableAudioOnTouch, { once: true });
                });
            }
        }

        tryAutoPlay();

        // প্লে/পজ বাটনে ক্লিকে ম্যানুয়াল কন্ট্রোল
        btn.addEventListener("click", function (e) {
            e.stopPropagation();
            if (audio.paused) {
                audio.play();
                updateUI(true);
            } else {
                audio.pause();
                updateUI(false);
            }
        });
    });
</script>

