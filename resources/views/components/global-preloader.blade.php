<style>
    #global-preloader {
        position: fixed;
        inset: 0;
        z-index: 999999;
        /* Transparent with blur (Glassmorphism) */
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s ease, visibility 0.5s ease;
        font-family: 'Inter', sans-serif;
    }

    .preloader-content {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    /* Pure CSS Logo mimicking the uploaded design */
    .css-logo-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
        animation: pulseLogo 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.1));
    }

    .css-logo-text {
        font-family: 'Segoe UI', Roboto, 'Inter', sans-serif;
        font-size: 72px;
        font-weight: 900;
        color: #102a43; /* Dark Navy Blue */
        line-height: 1;
        letter-spacing: -2px;
        margin-bottom: 5px;
    }

    .css-logo-text span {
        color: #00b4d8; /* Cyan */
    }

    .css-logo-tagline {
        font-size: 13px;
        font-weight: 700;
        color: #102a43;
        letter-spacing: 3px;
    }

    .css-logo-tagline span {
        color: #00b4d8;
    }

    .preloader-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(0, 180, 216, 0.3) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        z-index: 1;
        animation: pulseGlow 2.5s ease-in-out infinite;
        pointer-events: none;
    }

    .premium-spinner {
        margin-top: 40px;
        width: 36px;
        height: 36px;
        border: 3px solid rgba(0, 180, 216, 0.15);
        border-top-color: #00b4d8;
        border-radius: 50%;
        animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        z-index: 2;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes pulseLogo {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.03); opacity: 0.9; }
    }

    @keyframes pulseGlow {
        0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
        50% { transform: translate(-50%, -50%) scale(1.5); opacity: 0.8; }
    }
</style>

<div id="global-preloader">
    <div class="preloader-content">
        <div class="preloader-glow"></div>
        
        <!-- CSS Logo mimicking the design -->
        <div class="css-logo-container">
            <div class="css-logo-text">Freit<span>X</span></div>
            <div class="css-logo-tagline">LOGISTICS <span style="color:#cbd5e1">|</span> GLOBAL <span style="color:#cbd5e1">|</span> RELIABLE</div>
        </div>

        <div class="premium-spinner"></div>
    </div>
</div>

<script>
    (function() {
        let isInitialLoad = true;

        function showPreloader() {
            const preloader = document.getElementById('global-preloader');
            if(preloader) {
                preloader.style.visibility = 'visible';
                preloader.style.opacity = '1';
            }
        }

        function hidePreloader(instant = false) {
            const preloader = document.getElementById('global-preloader');
            if(preloader) {
                if (instant) {
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';
                } else {
                    preloader.style.opacity = '0';
                    setTimeout(() => { preloader.style.visibility = 'hidden'; }, 500);
                }
            }
        }

        // Initial browser page load: show for at least 1.2 seconds
        window.addEventListener('load', function() {
            setTimeout(() => {
                hidePreloader(false);
            }, 1200);
        });

        // Hard navigation unload
        window.addEventListener('beforeunload', function() {
            showPreloader();
        });

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                hidePreloader(true);
            }
        });

        // Hotwire Turbo navigation events
        document.addEventListener('turbo:request-start', function() {
            showPreloader();
        });

        document.addEventListener('turbo:load', function() {
            if (isInitialLoad) {
                // The first turbo:load fires on initial boot, let the window load handler manage the splash delay
                isInitialLoad = false;
            } else {
                // Subsequent Turbo transitions are fast, hide immediately
                hidePreloader(true);
            }
        });
    })();
</script>
