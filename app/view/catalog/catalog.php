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
    <title>Game Catalog - MabarStation</title>
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
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-glow-white font-orbitron">GAME CATALOG</h1>
        <p class="text-light opacity-50">Find your favorite games here</p>
    </div>

    <div class="search-container mb-5">
        <form method="GET" action="index.php" class="catalog-search-form">
            <!-- Ensure route parameters are sent as hidden fields so they aren't lost in GET form submit -->
            <input type="hidden" name="page" value="catalog">
            <div class="catalog-search-box">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="search"
                    class="catalog-search-input"
                    placeholder="Search games..."
                    value="<?= htmlspecialchars($search) ?>">
            </div>

            <button type="submit" class="catalog-search-button">
                Search
            </button>
        </form>
    </div>

    <div class="row g-4 mb-5">
        <?php if (mysqli_num_rows($hasil_query) > 0): ?>
            <?php while ($game = mysqli_fetch_assoc($hasil_query)): ?>
                <div class="col-md-4">
                    <div class="steam-card">
                        <div class="steam-card-inner">
                            <div class="steam-card-front">
                                <img src="assets/images/<?= $game['gambar'] ?>" alt="<?= $game['nama_game'] ?>" onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=400'">
                                <h4 class="steam-card-title"><?= $game['nama_game'] ?></h4>
                            </div>
                            <div class="steam-card-back flex-column">
                                <h4 class="mt-3"><?= $game['nama_game'] ?></h4>
                                <hr class="hr-card-white">
                                <p class="opacity-75 small p-3"><?= $game['deskripsi'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center text-white opacity-50">
                <h3>Game "<?= htmlspecialchars($search) ?>" was not found.</h3>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($total_halaman > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=catalog&halaman=<?= $halaman - 1 ?>&search=<?= urlencode($search) ?>">Prev</a>
                </li>

                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                    <li class="page-item <?= ($halaman == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="index.php?page=catalog&halaman=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : '' ?>">
                    <a class="page-link" href="index.php?page=catalog&halaman=<?= $halaman + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</main>

    <?php include 'app/view/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
