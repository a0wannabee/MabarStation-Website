<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MabarStation - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Ambient background shapes -->
    <div class="ambient-shape ambient-cross">×</div>
    <div class="ambient-shape ambient-circle">●</div>
    <div class="ambient-shape ambient-square">■</div>
    <div class="ambient-shape ambient-triangle">▲</div>

    <header class="sticky-top">
        <?php include 'app/view/partials/nav.php'; ?>
    </header>

    <main class="container my-5">
    <section class="row align-items-center mb-5 pb-5 mt-5">
        <div class="col-lg-6 text-center text-lg-start">
            <h1 class="display-3 fw-bolder mb-3 text-glow-white animate-fade">LEVEL UP YOUR HANGOUT.</h1>
            <p class="lead text-light opacity-75 mb-5 mx-auto mx-lg-0 hero-desc">
                Enjoy gaming in a comfortable place. From casual play sessions with friends to more serious gaming sessions, everything is here.
            </p>
            <a href="index.php?page=booking" class="btn-cyber">Book Now</a>
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0 text-center">
            <img src="assets/images/hero_ps5.png" alt="Setup PS5 MabarStation" class="img-fluid hero-img" onerror="this.src='https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=600'">
        </div>
    </section>

    <section class="home-section">
        <div class="text-center section-heading">
            <h2 class="section-title">Console Lineup</h2>
            <p class="section-subtitle">Choose the console that matches your gaming mood.</p>
        </div>
        <div class="row g-4">
        <div class="col-md-4">
            <div class="glass-panel console-card home-feature-card h-100 d-flex flex-column">
                <div class="console-card-hero" style="background-image: url('assets/images/consoles/ps3-card.png');">
                    <div class="console-card-overlay"></div>

                    <div class="console-card-head">
                        <div>
                            <h2 class="console-card-title mb-0">
                                <i class="bi bi-playstation me-2"></i>PS3
                            </h2>
                            <p class="console-card-subtitle mb-0">PlayStation 3</p>
                        </div>
                    </div>

                    <h3 class="console-card-price">
                        Rp 10.000
                        <span>/Hour</span>
                    </h3>
                </div>

                <div class="console-card-body">
                    <ul class="console-feature-list">
                        <li>2 Wireless Controllers</li>
                        <li>Full Digital Games</li>
                        <li>Perfect for nostalgia</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel console-card home-feature-card h-100 d-flex flex-column">
                <div class="console-card-hero" style="background-image: url('assets/images/consoles/ps4-card.png');">
                    <div class="console-card-overlay"></div>

                    <div class="console-card-head">
                        <div>
                            <h2 class="console-card-title mb-0">
                                <i class="bi bi-playstation me-2"></i>PS4
                            </h2>
                            <p class="console-card-subtitle mb-0">PlayStation 4</p>
                        </div>
                    </div>

                    <h3 class="console-card-price">
                        Rp 15.000
                        <span>/Hour</span>
                    </h3>
                </div>

                <div class="console-card-body">
                    <ul class="console-feature-list">
                        <li>2 Wireless Controllers</li>
                        <li>FIFA & PES Games</li>
                        <li>HD Graphics</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel console-card console-recommended-card featured-card home-feature-card h-100 d-flex flex-column">
                <div class="console-card-hero" style="background-image: url('assets/images/consoles/ps5-card.png');">
                    <div class="console-card-overlay"></div>

                    <div class="console-card-head">
                        <div>
                            <h2 class="console-card-title mb-0">
                                <i class="bi bi-playstation me-2"></i>PS5
                            </h2>
                            <p class="console-card-subtitle mb-0">PlayStation 5</p>
                        </div>

                        <span class="recommendation-badge">RECOMMENDED</span>
                    </div>

                    <h3 class="console-card-price">
                        Rp 25.000
                        <span>/Hour</span>
                    </h3>
                </div>

                <div class="console-card-body">
                    <ul class="console-feature-list">
                        <li>2 Stick DualSense</li>
                        <li>Latest Game Collection</li>
                        <li>4K 60FPS Graphics</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </section>

    <section class="home-section">
        <div class="text-center section-heading">
            <h2 class="section-title">Room Setup</h2>
            <p class="section-subtitle">Pick the perfect space for your gaming session.</p>
        </div>
        <div class="row g-4">
        <div class="col-md-6">
            <div class="glass-panel room-showcase-card regular-room-card home-feature-card h-100">
                <div class="room-card-image-wrap">
                    <img src="assets/images/rooms/regular-room.png" alt="Regular Space" class="room-card-image">
                </div>

                <div class="room-card-content">
                    <div class="room-card-header room-card-header-regular">
                        <h4 class="room-card-title">Regular Space</h4>
                    </div>

                    <div class="room-card-separator"></div>

                    <p class="room-card-description">
                        A spacious open area. An ideal setup for group gaming without feeling cramped or overheated.
                    </p>

                    <ul class="room-feature-list">
                        <li>Display: 32-inch HD LED TV</li>
                        <li>Cooling: High-Airflow Fan</li>
                        <li>Seating: Ergonomic Standard Sofa</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass-panel recommendation-card room-showcase-card vip-room-card featured-card home-feature-card h-100">
                <div class="room-card-image-wrap">
                    <img src="assets/images/rooms/vip-room.png" alt="VIP Lounge" class="room-card-image">
                </div>

                <div class="room-card-content">
                    <div class="room-card-header room-card-header-vip">
                        <h4 class="room-card-title">VIP Lounge</h4>
                        <span class="room-card-badge">BEST EXPERIENCE</span>
                    </div>

                    <div class="room-card-separator"></div>

                    <p class="room-card-description">
                        A private room for a more exclusive gaming experience. Free from outside distractions, cooler, and definitely more comfortable.
                    </p>

                    <ul class="room-feature-list">
                        <li>Display: 50-inch 4K UHD Smart TV</li>
                        <li>Cooling: Dedicated AC</li>
                        <li>Perks: Premium Sofa Bed & Free Snacks</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </section>

    <section class="home-section">
        <div class="text-center section-heading">
            <h2 class="section-title">Visitor Reviews</h2>
            <p class="section-subtitle">See what players say after spending their session at MabarStation.</p>
        </div>
    <?php if (empty($reviews)): ?>
        <div class="text-center text-white opacity-50 my-5">
            <p class="lead">No reviews yet.</p>
        </div>
    <?php else: ?>
        <div class="reviews-slider-wrapper position-relative py-4">
            <div class="reviews-slider-track-container">
                <div class="reviews-slider-track">
                    <?php foreach ($reviews as $row): ?>
                        <div class="reviews-slider-card">
                            <div class="glass-panel review-card h-100 d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-dark border border-secondary d-flex align-items-center justify-content-center me-3 font-orbitron text-glow-white fw-bold" style="width: 50px; height: 50px; font-size: 1.25rem; flex-shrink: 0;">
                                        <?= htmlspecialchars(strtoupper(substr($row['visitor_name'], 0, 1))) ?>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-white"><?= htmlspecialchars($row['visitor_name']) ?></h5>
                                        <small class="text-white opacity-50"><?= htmlspecialchars($row['playstation_type']) ?> - <?= htmlspecialchars($row['room_type']) ?></small>
                                    </div>
                                </div>
                                <div class="mb-3 text-glow-white">
                                    <?php 
                                    $rating = intval($row['rating']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="bi bi-star-fill me-1"></i>';
                                        } else {
                                            echo '<i class="bi bi-star me-1"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <p class="text-light opacity-75 mb-0" style="font-style: italic; font-size: 1.05rem; line-height: 1.5;">
                                    "<?= htmlspecialchars($row['review_text']) ?>"
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (count($reviews) > 1): ?>
                <!-- Navigation Buttons -->
                <button class="reviews-prev position-absolute top-50 start-0 translate-middle-y border-0 bg-transparent" style="z-index: 10; margin-left: -40px;">
                    <i class="bi bi-chevron-left text-glow-white fs-1"></i>
                </button>
                <button class="reviews-next position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent" style="z-index: 10; margin-right: -40px;">
                    <i class="bi bi-chevron-right text-glow-white fs-1"></i>
                </button>
                
                <!-- Carousel Indicators (Dots) -->
                <div class="reviews-indicators d-flex justify-content-center align-items-center mt-4">
                    <!-- Filled by JS -->
                </div>
            <?php endif; ?>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const track = document.querySelector('.reviews-slider-track');
            const cards = document.querySelectorAll('.reviews-slider-card');
            const prevBtn = document.querySelector('.reviews-prev');
            const nextBtn = document.querySelector('.reviews-next');
            const indicatorsContainer = document.querySelector('.reviews-indicators');
            
            if (!track || cards.length === 0) return;
            
            let currentIndex = 0;
            
            function getVisibleCardsCount() {
                if (window.innerWidth <= 576) return 1;
                if (window.innerWidth <= 991) return 2;
                return 3;
            }
            
            function buildIndicators() {
                if (!indicatorsContainer) return;
                indicatorsContainer.innerHTML = '';
                const visibleCards = getVisibleCardsCount();
                const totalSlides = Math.max(1, cards.length - visibleCards + 1);
                
                if (cards.length <= visibleCards) {
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    return;
                } else {
                    if (prevBtn) prevBtn.style.display = 'block';
                    if (nextBtn) nextBtn.style.display = 'block';
                }
                
                for (let i = 0; i < totalSlides; i++) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.style.width = '10px';
                    btn.style.height = '10px';
                    btn.style.borderRadius = '50%';
                    btn.style.border = 'none';
                    btn.style.margin = '0 6px';
                    btn.style.backgroundColor = '#ffffff';
                    btn.style.opacity = i === currentIndex ? '1' : '0.35';
                    btn.style.boxShadow = i === currentIndex ? '0 0 8px #ffffff' : 'none';
                    btn.style.transition = 'opacity 0.3s ease, box-shadow 0.3s ease';
                    
                    btn.addEventListener('click', () => {
                        currentIndex = i;
                        updateSlider();
                    });
                    indicatorsContainer.appendChild(btn);
                }
            }
            
            function updateSlider() {
                const visibleCards = getVisibleCardsCount();
                const maxIndex = Math.max(0, cards.length - visibleCards);
                if (currentIndex > maxIndex) {
                    currentIndex = maxIndex;
                }
                
                if (cards.length <= visibleCards) {
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                } else {
                    if (prevBtn) prevBtn.style.display = 'block';
                    if (nextBtn) nextBtn.style.display = 'block';
                }
                
                const cardRect = cards[0].getBoundingClientRect();
                const cardWidth = cardRect.width;
                const gap = 24;
                const offset = currentIndex * (cardWidth + gap);
                track.style.transform = `translateX(-${offset}px)`;
                
                if (prevBtn && prevBtn.style.display !== 'none') {
                    prevBtn.style.opacity = currentIndex === 0 ? '0.3' : '1';
                    prevBtn.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
                }
                if (nextBtn && nextBtn.style.display !== 'none') {
                    nextBtn.style.opacity = currentIndex === maxIndex ? '0.3' : '1';
                    nextBtn.style.pointerEvents = currentIndex === maxIndex ? 'none' : 'auto';
                }
                
                if (indicatorsContainer) {
                    const indicators = indicatorsContainer.querySelectorAll('button');
                    indicators.forEach((btn, i) => {
                        btn.style.opacity = i === currentIndex ? '1' : '0.35';
                        btn.style.boxShadow = i === currentIndex ? '0 0 8px #ffffff' : 'none';
                    });
                }
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const visibleCards = getVisibleCardsCount();
                    if (currentIndex < cards.length - visibleCards) {
                        currentIndex++;
                        updateSlider();
                    }
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateSlider();
                    }
                });
            }
            
            window.addEventListener('resize', () => {
                buildIndicators();
                updateSlider();
            });
            
            buildIndicators();
            updateSlider();
        });
        </script>
    <?php endif; ?>
    </section>
</main>

    <?php include 'app/view/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
