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
    <title>Account Login - MabarStation</title>
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

    <main class="container my-auto py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="glass-panel auth-form-panel">
                <div class="text-center mb-4">
                    <h2 class="text-glow-white">Account Login</h2>
                    <p class="text-light opacity-50">Welcome back to MabarStation</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger custom-alert mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success custom-alert mb-4">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>


                <form method="POST" action="index.php?page=login">
                    <div class="mb-3">
                        <label class="form-label text-light opacity-75">Email Address:</label>
                        <input type="email" name="email" required class="form-control glass-input" placeholder="name@example.com" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light opacity-75">Password:</label>
                        <input type="password" name="password" required class="form-control glass-input" placeholder="Enter password">
                    </div>

                    <button type="submit" class="btn-cyber auth-form-submit">LOGIN</button>
                </form>

                <div class="text-center mt-3">
                    <p class="mb-0 text-light opacity-75">Don't have an account? <a href="index.php?page=register" class="text-glow-white text-decoration-none fw-bold">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

    <?php include 'app/view/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
