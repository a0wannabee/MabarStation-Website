<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
?>
<nav class="navbar navbar-expand-lg navbar-custom py-3">
    <div class="container">
        <a class="navbar-brand fw-bold font-orbitron text-white d-flex align-items-center" href="index.php?page=home">
            <img src="assets/images/logo.png" alt="MabarStation Logo" style="width: 42px; height: auto; margin-right: 12px;">
            MabarStation
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navData">
            <i class="bi bi-list text-glow-white fs-1"></i>
        </button>

        <div class="collapse navbar-collapse" id="navData">
            <ul class="navbar-nav me-auto ms-lg-4">
                <li class="nav-item"><a class="nav-link text-white text-uppercase" href="index.php?page=catalog">GAME CATALOG</a></li>
                <li class="nav-item"><a class="nav-link text-white text-uppercase" href="index.php?page=booking">BOOKING</a></li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="user-dropdown-toggle d-flex align-items-center gap-2 dropdown-toggle font-orbitron text-white" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            $pic = !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'uploads/default.png';
                            if (!file_exists($pic)) {
                                $pic = 'uploads/default.png';
                            }
                            ?>
                            <img src="<?= htmlspecialchars($pic) ?>" alt="Profile Picture" class="rounded-circle border border-secondary" style="width: 30px; height: 30px; object-fit: cover;">
                            <span class="text-glow-white fw-bold"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </a>
                        <ul class="dropdown-menu user-dropdown-menu dropdown-menu-end border-secondary" aria-labelledby="navbarUserDropdown">
                            <li><a class="dropdown-item font-orbitron" href="index.php?page=profile">PROFILE</a></li>
                            <li>
                                <hr class="dropdown-divider border-secondary">
                            </li>
                            <li><a class="dropdown-item text-white opacity-75 font-orbitron" href="index.php?page=logout">LOGOUT</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item auth-actions">
                        <a class="nav-auth-btn" href="index.php?page=login">LOGIN</a>
                        <a class="nav-auth-btn nav-auth-primary" href="index.php?page=register">REGISTER</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
