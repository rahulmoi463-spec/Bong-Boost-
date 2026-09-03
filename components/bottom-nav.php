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
<!-- Bong Boost Official Floating Music Player Start -->
<audio id="bgMusic" loop preload="auto">
    <source src="https://stream.zeno.fm/f3wvbbqmdg8uv" type="audio/mpeg">
</audio>

<!-- Official Brand Floating Widget -->
<div id="musicControlWidget" style="position: fixed; bottom: 65px; right: 12px; z-index: 99999; display: flex; align-items: center; gap: 8px; background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(56, 189, 248, 0.3); backdrop-filter: blur(8px); padding: 5px 12px; border-radius: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
    
    <!-- Animated Sound Wave/Pulse Icon -->
    <div id="musicWave" style="display: flex; align-items: flex-end; gap: 2px; height: 12px;">
        <span class="bar" style="width: 2px; height: 100%; background: #38bdf8; border-radius: 1px;"></span>
        <span class="bar" style="width: 2px; height: 60%; background: #38bdf8; border-radius: 1px;"></span>
        <span class="bar" style="width: 2px; height: 80%; background: #38bdf8; border-radius: 1px;"></span>
    </div>

    <!-- Play/Pause Button -->
    <button id="musicToggleBtn" style="background: #ef4444; border: none; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; outline: none; box-shadow: 0 0 8px rgba(239, 68, 68, 0.5);">
        <span id="playIcon" style="display: none; font-size: 11px; margin-left: 1px;">▶</span>
        <span id="pauseIcon" style="display: inline; font-size: 10px;">⏸</span>
    </button>
    
    <!-- Official Panel Name & Icon -->
    <span id="musicStatusText" style="color: #f8fafc; font-size: 11px; font-weight: 700; font-family: sans-serif; letter-spacing: 0.3px; display: flex; align-items: center; gap: 4px;">
        Bong Boost 🎵
    </span>
</div>

<style>
    @keyframes pulseWave {
        0%, 100% { height: 30%; }
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
                btn.style.background = "#ef4444";
                btn.style.boxShadow = "0 0 8px rgba(239, 68, 68, 0.5)";
                wave.classList.add("playing");
            } else {
                playIcon.style.display = "inline";
                pauseIcon.style.display = "none";
                btn.style.background = "#10b981";
                btn.style.boxShadow = "0 0 8px rgba(16, 185, 129, 0.5)";
                wave.classList.remove("playing");
            }
        }

        function tryAutoPlay() {
            var promise = audio.play();
            if (promise !== undefined) {
                promise.then(function() {
                    updateUI(true);
                }).catch(function(error) {
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
<!-- Bong Boost Official Floating Music Player End -->
        
