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
    <title>User Profile - MabarStation</title>
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

    <main class="container my-5 py-3">
    <div class="row justify-content-center">
        <!-- Left Column: Avatar & Quick Info -->
        <div class="col-md-4 mb-4">
            <div class="glass-panel profile-card text-center">
                <div class="text-center mb-4">
                    <h2 class="text-glow-white">User Profile</h2>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger custom-alert mb-4 text-start">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success custom-alert mb-4 text-start">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>


                <?php 
                $pic = !empty($user['profile_picture']) ? $user['profile_picture'] : 'uploads/default.png';
                if (!file_exists($pic)) {
                    $pic = 'uploads/default.png';
                }
                ?>
                <img src="<?= htmlspecialchars($pic) ?>" alt="Profile Picture" class="profile-avatar mb-4">
                
                <h3 class="text-white mb-2 font-orbitron text-glow-white"><?= htmlspecialchars($user['username']) ?></h3>
                
                <hr class="hr-white mb-4">

                <!-- Photo Upload Section -->
                <form method="POST" action="index.php?page=profile" enctype="multipart/form-data" class="text-start mb-2">
                    <div class="mb-4">
                        <label class="form-label text-light opacity-75 small">Update Profile Picture (Max 2MB, JPG/PNG/WEBP):</label>
                        <input type="file" name="profile_picture" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" required>
                    </div>

                    <button type="submit" class="btn btn-cyber w-100">
                        Upload Photo
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Tabs Panel -->
        <div class="col-md-8">
            <div class="glass-panel">
                <!-- Tabs Header -->
                <div class="profile-tabs">
                    <button type="button" class="profile-tab-btn active" data-target="#account-info-tab">Account Info</button>
                    <button type="button" class="profile-tab-btn" data-target="#my-bookings-tab">My Bookings</button>
                </div>

                <!-- Tab 1: Account Info -->
                <div class="profile-tab-content active" id="account-info-tab">
                    <h4 class="text-glow-white mb-4">Account Details</h4>
                    <div class="p-3 bg-dark bg-opacity-50 rounded border border-secondary mb-3">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-light opacity-50">Username:</div>
                            <div class="col-sm-8 text-white fw-bold"><?= htmlspecialchars($user['username']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-light opacity-50">Email:</div>
                            <div class="col-sm-8 text-white"><?= htmlspecialchars($user['email']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-light opacity-50">Phone Number:</div>
                            <div class="col-sm-8 text-white"><?= htmlspecialchars($user['phone_number'] ?? '-') ?></div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-sm-4 text-light opacity-50">Joined Date:</div>
                            <div class="col-sm-8 text-white"><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: My Bookings -->
                <div class="profile-tab-content" id="my-bookings-tab">
                    <h4 class="text-glow-white mb-4">Booking History</h4>
                    
                    <?php if (empty($bookings)): ?>
                        <div class="p-4 text-center rounded border border-secondary text-light opacity-50">
                            <i class="bi bi-calendar-x fs-1 mb-2 d-block text-glow-white"></i>
                            Kamu belum memiliki booking.
                        </div>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <div class="profile-booking-card">
                                <div class="profile-booking-head">
                                    <h5 class="text-glow-white m-0 font-orbitron text-uppercase fs-6"><?= htmlspecialchars($booking['booking_id']) ?></h5>
                                    <?php $statusClass = strtolower($booking['status']); ?>
                                    <span class="booking-status <?= htmlspecialchars($statusClass) ?>">
                                        <?= htmlspecialchars(ucfirst($booking['status'])) ?>
                                    </span>
                                </div>
                                <div class="row text-light opacity-75 small font-rajdhani mt-2">
                                    <div class="col-sm-6 mb-1">
                                        <i class="bi bi-controller me-2 text-glow-white"></i><strong>Console:</strong> <?= htmlspecialchars($booking['playstation_name']) ?>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <i class="bi bi-door-closed me-2 text-glow-white"></i><strong>Room:</strong> <?= htmlspecialchars($booking['room_name']) ?>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <i class="bi bi-calendar-event me-2 text-glow-white"></i><strong>Date:</strong> <?= date('d M Y', strtotime($booking['booking_date'])) ?>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <i class="bi bi-clock me-2 text-glow-white"></i><strong>Time:</strong> <?= date('H:i', strtotime($booking['start_time'])) ?> - <?= date('H:i', strtotime($booking['end_time'])) ?>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <i class="bi bi-hourglass-split me-2 text-glow-white"></i><strong>Duration:</strong> <?= htmlspecialchars($booking['duration']) ?> Hour(s)
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <i class="bi bi-wallet2 me-2 text-glow-white"></i><strong>Total:</strong> Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
