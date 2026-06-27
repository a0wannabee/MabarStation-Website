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
    <title>Create Account - MabarStation</title>
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
                    <h2 class="text-glow-white">Create Account</h2>
                    <p class="text-light opacity-50">Join MabarStation today</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger custom-alert mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>


                <form method="POST" action="index.php?page=register">
                    <div class="mb-3">
                        <label class="form-label text-light opacity-75">Username:</label>
                        <input type="text" name="username" required class="form-control glass-input" placeholder="Enter username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light opacity-75">Email:</label>
                        <input type="email" name="email" required class="form-control glass-input" placeholder="name@example.com" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light opacity-75">Phone Number:</label>
                        <input
                            type="tel"
                            name="phone_number"
                            required
                            class="form-control glass-input"
                            placeholder="Contoh: 081234567890"
                            pattern="[0-9]{10,15}"
                            inputmode="numeric"
                            maxlength="15"
                            value="<?= isset($phone_number) ? htmlspecialchars($phone_number) : '' ?>"
                        >
    
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light opacity-75">Password:</label>
                        <input type="password" name="password" required class="form-control glass-input" placeholder="Minimum 6 characters">
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light opacity-75">Confirm Password:</label>
                        <input type="password" name="confirm_password" required class="form-control glass-input" placeholder="Confirm your password">
                    </div>

                    <button type="submit" class="btn-cyber auth-form-submit">REGISTER</button>
                </form>

                <div class="text-center mt-3">
                    <p class="mb-0 text-light opacity-75">Already have an account? <a href="index.php?page=login" class="text-glow-white text-decoration-none fw-bold">Login here</a></p>
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
