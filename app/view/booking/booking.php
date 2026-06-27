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
    <title>Rental Booking - MabarStation</title>
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
        <div class="col-lg-8 col-md-10">

            <div class="text-center mb-4">
                <h2 class="text-glow-white">Rental Booking Form</h2>
            </div>

            <div class="glass-panel booking-form-panel">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger custom-alert mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger custom-alert mb-4">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success custom-alert mb-4">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>


                <form method="POST" action="index.php?page=booking" id="bookingForm">
                    <!-- Hidden values for chosen cards -->
                    <input type="hidden" name="playstation_id" id="selectedPlaystation">
                    <input type="hidden" name="room_type" id="selectedRoomType">
                    <input type="hidden" name="room_id" id="selectedRoomId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light opacity-75">Customer Name:</label>
                            <input 
                                type="text" 
                                name="username" 
                                class="form-control glass-input"
                                value="<?= htmlspecialchars($currentUser['username'] ?? '') ?>"
                                readonly
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light opacity-75">Phone Number:</label>
                            <input 
                                type="tel" 
                                name="phone_number" 
                                class="form-control glass-input"
                                value="<?= htmlspecialchars($currentUser['phone_number'] ?? '') ?>"
                                pattern="[0-9]{10,15}"
                                inputmode="numeric"
                                maxlength="15"
                                readonly
                            >
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <label class="form-label text-light opacity-75">Select Date:</label>
                            <input type="date" id="booking_date" name="booking_date" required class="form-control glass-input" min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <label class="form-label text-light opacity-75">Select Start Time:</label>
                            <input type="time" id="start_time" name="start_time" required class="form-control glass-input">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label text-light opacity-75">Duration (Hours):</label>
                            <input type="number" id="duration_hours" name="duration_hours" min="1" max="12" required class="form-control glass-input" placeholder="1" value="1">
                        </div>
                    </div>

                    <!-- Card Options Selection for PlayStation -->
                    <section class="bw-booking-section">
                        <div class="bw-booking-header">
                            <span>STEP 1</span>
                            <h3>Choose PlayStation</h3>
                            <p>Select one console for your gaming session.</p>
                        </div>

                        <div class="bw-booking-grid bw-console-grid">
                            <?php foreach ($playstations as $ps): ?>
                                <?php 
                                $is_disabled = ($ps['status'] !== 'available' || $ps['stock'] <= 0);
                                $title = "PlayStation " . trim(str_ireplace('ps', '', $ps['type']));
                                ?>
                                <button type="button"
                                        class="bw-choice-card"
                                        data-choice="playstation"
                                        data-id="<?= htmlspecialchars($ps['id']) ?>"
                                        data-price="<?= $ps['price_per_hour'] ?>"
                                        <?= $is_disabled ? 'disabled' : '' ?>>
                                    <div class="bw-choice-top">
                                        <span class="bw-choice-label">CONSOLE</span>
                                        <span class="bw-choice-status <?= $is_disabled ? 'unavailable' : 'available' ?>"><?= $is_disabled ? 'Unavailable' : 'Available' ?></span>
                                    </div>

                                    <div class="bw-choice-body">
                                        <div class="bw-choice-code"><?= htmlspecialchars($ps['type']) ?></div>
                                        <div class="bw-choice-content">
                                            <h4 class="bw-choice-title"><?= htmlspecialchars($title) ?></h4>
                                            <p class="bw-choice-desc"><?= htmlspecialchars($ps['description']) ?></p>
                                        </div>
                                    </div>

                                    <div class="bw-choice-meta">
                                        <span class="bw-choice-price">Rp <?= number_format($ps['price_per_hour'], 0, ',', '.') ?> / Hour</span>
                                        <span class="bw-choice-slot" id="slot_info_ps_<?= htmlspecialchars($ps['id']) ?>"><?= $ps['stock'] ?> units left</span>
                                    </div>

                                    <span class="bw-choice-check">✓</span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- Card Options Selection for Room -->
                    <section class="bw-booking-section">
                        <div class="bw-booking-header">
                            <span>STEP 2</span>
                            <h3>Choose Room</h3>
                            <p>Pick one room type for your group.</p>
                        </div>

                        <div class="bw-booking-grid bw-room-grid">
                            <!-- Regular Space Card -->
                            <?php $reg_disabled = ($total_regular <= 0); ?>
                            <button type="button"
                                    class="bw-choice-card bw-room-card <?= $reg_disabled ? 'is-disabled' : '' ?>"
                                    data-choice="room"
                                    data-room-type="Regular"
                                    data-price="0"
                                    <?= $reg_disabled ? 'disabled' : '' ?>>
                                <div class="bw-choice-top">
                                    <span class="bw-choice-label">ROOM</span>
                                    <span class="bw-choice-status <?= $reg_disabled ? 'unavailable' : 'available' ?>"><?= $reg_disabled ? 'Unavailable' : 'Available' ?></span>
                                </div>

                                <div class="bw-choice-body">
                                    <div class="bw-choice-code">REG</div>
                                    <div class="bw-choice-content">
                                        <h4 class="bw-choice-title">Regular Space</h4>
                                        <p class="bw-choice-desc">Open gaming area with HD TV, sofa, and fan cooling.</p>
                                    </div>
                                </div>

                                <div class="bw-slot-meter">
                                    <div class="bw-slot-meter-fill" id="meter_fill_Regular" style="width: 100%;"></div>
                                </div>

                                <div class="bw-choice-meta">
                                    <span class="bw-choice-price">Rp 0 additional</span>
                                    <span class="bw-choice-slot" id="slot_info_Regular"><?= $total_regular ?> of <?= $total_regular ?> slots left</span>
                                </div>

                                <span class="bw-choice-check">✓</span>
                            </button>

                            <!-- VIP Lounge Card -->
                            <?php $vip_disabled = ($total_vip <= 0); ?>
                            <button type="button"
                                    class="bw-choice-card bw-room-card <?= $vip_disabled ? 'is-disabled' : '' ?>"
                                    data-choice="room"
                                    data-room-type="VIP"
                                    data-price="10000"
                                    <?= $vip_disabled ? 'disabled' : '' ?>>
                                <div class="bw-choice-top">
                                    <span class="bw-choice-label">ROOM</span>
                                    <span class="bw-choice-status <?= $vip_disabled ? 'unavailable' : 'available' ?>"><?= $vip_disabled ? 'Unavailable' : 'Available' ?></span>
                                </div>

                                <div class="bw-choice-body">
                                    <div class="bw-choice-code">VIP</div>
                                    <div class="bw-choice-content">
                                        <h4 class="bw-choice-title">VIP Lounge</h4>
                                        <p class="bw-choice-desc">Private room with 4K TV, AC, premium sofa, and snacks.</p>
                                    </div>
                                </div>

                                <div class="bw-slot-meter">
                                    <div class="bw-slot-meter-fill" id="meter_fill_VIP" style="width: 100%;"></div>
                                </div>

                                <div class="bw-choice-meta">
                                    <span class="bw-choice-price">Rp 10.000 additional</span>
                                    <span class="bw-choice-slot" id="slot_info_VIP"><?= $total_vip ?> of <?= $total_vip ?> slots left</span>
                                </div>

                                <span class="bw-choice-check">✓</span>
                            </button>
                        </div>
                    </section>

                    <div class="mb-3">
                        <label class="form-label text-light opacity-75">Notes:</label>
                        <textarea name="notes" rows="3" class="form-control glass-input" placeholder="Optional notes..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light opacity-75">Estimated Total Price:</label>
                        <input type="text" id="total_price" readonly class="form-control glass-input text-glow-white fw-bold fs-5" value="Rp 0">
                    </div>

                    <button type="submit" class="btn-cyber btn-booking-submit" <?= ($total_regular <= 0 && $total_vip <= 0) ? 'disabled' : '' ?>>
                        Submit Booking
                    </button>
                </form>
            </div>

        </div>
    </div>
</main>

<!-- Booking Success Modal -->
<div class="modal fade" id="bookingSuccessModal" tabindex="-1" aria-labelledby="bookingSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-white font-orbitron" style="border-radius: 15px; box-shadow: 0 0 15px rgba(255, 255, 255, 0.25);">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-glow-white" id="bookingSuccessModalLabel">
                    <i class="bi bi-check-circle-fill me-2 text-white"></i>Booking Successful
                </h5>
            </div>
            <div class="modal-body text-light font-rajdhani fs-5 py-4">
                <p>Your booking has been created successfully.</p>
                <div class="p-3 bg-black rounded border border-secondary mb-2">
                    <span class="text-white opacity-75">Total Price:</span>
                    <span class="text-glow-white fw-bold float-end fs-4" id="success_modal_price">
                        Rp <?= number_format($booking_total_price, 0, ',', '.') ?>
                    </span>
                </div>
            </div>
            <div class="modal-footer border-secondary justify-content-center">
                <a href="index.php?page=home" class="btn btn-cyber" style="width: 120px; font-size: 0.95rem;">Home</a>
            </div>
        </div>
    </div>
</div>

<script>
function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(num).replace('Rp', 'Rp ');
}

function calculatePrice() {
    const psInput = document.getElementById('selectedPlaystation');
    const roomInput = document.getElementById('selectedRoomType');
    const durationInput = document.getElementById('duration_hours');
    const totalPriceInput = document.getElementById('total_price');

    if (!psInput || !roomInput || !durationInput || !totalPriceInput) return;

    const duration = parseInt(durationInput.value) || 0;
    
    let psPrice = 0;
    const selectedPsCard = document.querySelector(`.bw-choice-card[data-choice="playstation"].selected`);
    if (selectedPsCard) {
        psPrice = parseInt(selectedPsCard.getAttribute('data-price')) || 0;
    }

    let roomExtra = 0;
    const selectedRoomCard = document.querySelector(`.bw-choice-card[data-choice="room"].selected`);
    if (selectedRoomCard) {
        roomExtra = parseInt(selectedRoomCard.getAttribute('data-price')) || 0;
    }

    if (duration > 0 && psInput.value && roomInput.value) {
        const total = (psPrice * duration) + roomExtra;
        totalPriceInput.value = formatRupiah(total);
    } else {
        totalPriceInput.value = 'Rp 0';
    }
}

function updateOptionsAvailability(data) {
    const psInput = document.getElementById('selectedPlaystation');
    const roomInput = document.getElementById('selectedRoomType');

    let selectedPsStillAvailable = false;
    let selectedRoomStillAvailable = false;

    // 1. Update PlayStation Cards
    data.playstations.forEach(ps => {
        const card = document.querySelector(`.bw-choice-card[data-choice="playstation"][data-id="${ps.id}"]`);
        if (card) {
            const pill = card.querySelector('.bw-choice-status');
            const slotInfo = card.querySelector('.bw-choice-slot');
            
            if (!ps.is_available) {
                card.classList.add('is-disabled');
                card.setAttribute('disabled', 'true');
                card.classList.remove('selected');
                if (pill) {
                    pill.textContent = 'Unavailable';
                    pill.classList.remove('available');
                    pill.classList.add('unavailable');
                }
                if (slotInfo) {
                    slotInfo.textContent = '0 units left';
                }
            } else {
                card.classList.remove('is-disabled');
                card.removeAttribute('disabled');
                if (pill) {
                    pill.textContent = 'Available';
                    pill.classList.remove('unavailable');
                    pill.classList.add('available');
                }
                if (slotInfo) {
                    slotInfo.textContent = `${ps.remaining_stock} units left`;
                }
                if (ps.id == psInput.value) {
                    selectedPsStillAvailable = true;
                }
            }
        }
    });

    if (!selectedPsStillAvailable) {
        psInput.value = '';
        document.querySelectorAll('.bw-choice-card[data-choice="playstation"]').forEach(c => c.classList.remove('selected'));
    }

    // 2. Update Room Cards
    data.rooms.forEach(rm => {
        const card = document.querySelector(`.bw-choice-card[data-choice="room"][data-room-type="${rm.room_type}"]`);
        if (card) {
            const pill = card.querySelector('.bw-choice-status');
            const slotInfo = document.getElementById(`slot_info_${rm.room_type}`);
            const meterFill = document.getElementById(`meter_fill_${rm.room_type}`);

            const percentage = rm.total_slots > 0 ? (rm.remaining_slots / rm.total_slots) * 100 : 0;
            if (meterFill) {
                meterFill.style.width = `${percentage}%`;
            }

            if (slotInfo) {
                slotInfo.textContent = `${rm.remaining_slots} of ${rm.total_slots} slots left`;
            }

            if (!rm.is_available) {
                card.classList.add('is-disabled');
                card.setAttribute('disabled', 'true');
                card.classList.remove('selected');
                if (pill) {
                    pill.textContent = 'Unavailable';
                    pill.classList.remove('available');
                    pill.classList.add('unavailable');
                }
            } else {
                card.classList.remove('is-disabled');
                card.removeAttribute('disabled');
                if (pill) {
                    pill.textContent = 'Available';
                    pill.classList.remove('unavailable');
                    pill.classList.add('available');
                }
                if (rm.room_type === roomInput.value) {
                    selectedRoomStillAvailable = true;
                }
            }
        }
    });

    if (!selectedRoomStillAvailable) {
        roomInput.value = '';
        document.querySelectorAll('.bw-choice-card[data-choice="room"]').forEach(c => c.classList.remove('selected'));
    }

    calculatePrice();
}

function fetchAvailability() {
    const dateInput = document.getElementById('booking_date');
    const timeInput = document.getElementById('start_time');
    const durationInput = document.getElementById('duration_hours');
    
    if (!dateInput || !timeInput || !durationInput) return;
    
    const date = dateInput.value;
    const time = timeInput.value;
    const duration = durationInput.value;
    
    if (!date || !time || !duration) {
        return;
    }
    
    fetch(`index.php?page=check_availability&date=${date}&time=${time}&duration=${duration}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateOptionsAvailability(data);
            }
        })
        .catch(err => console.error('Error fetching availability:', err));
}

document.addEventListener('DOMContentLoaded', () => {
    const psInput = document.getElementById('selectedPlaystation');
    const roomInput = document.getElementById('selectedRoomType');
    const dateInput = document.getElementById('booking_date');
    const timeInput = document.getElementById('start_time');
    const durationInput = document.getElementById('duration_hours');
    const bookingForm = document.getElementById('bookingForm');

    // Atur pilihan card PlayStation.
    document.querySelectorAll('.bw-choice-card[data-choice="playstation"]').forEach(card => {
        card.addEventListener('click', function() {
            if (this.disabled || this.classList.contains('is-disabled')) return;
            
            document.querySelectorAll('.bw-choice-card[data-choice="playstation"]').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            psInput.value = this.getAttribute('data-id');
            calculatePrice();
        });
    });

    // Atur pilihan card Room.
    document.querySelectorAll('.bw-choice-card[data-choice="room"]').forEach(card => {
        card.addEventListener('click', function() {
            if (this.disabled || this.classList.contains('is-disabled')) return;
            
            document.querySelectorAll('.bw-choice-card[data-choice="room"]').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            roomInput.value = this.getAttribute('data-room-type');
            calculatePrice();
        });
    });

    // Listener perubahan input untuk cek ketersediaan slot.
    if (dateInput) {
        dateInput.addEventListener('change', fetchAvailability);
    }
    if (timeInput) {
        timeInput.addEventListener('change', fetchAvailability);
    }
    if (durationInput) {
        durationInput.addEventListener('change', fetchAvailability);
        durationInput.addEventListener('input', fetchAvailability);
    }

    // Validasi form sebelum dikirim.
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            if (!psInput.value || !roomInput.value) {
                e.preventDefault();
                alert('Please select both a PlayStation console and a Room type before submitting.');
            }
        });
    }
    
    calculatePrice();
});
</script>
<?php if ($success === "success"): ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const successModal = new bootstrap.Modal(document.getElementById('bookingSuccessModal'));
        successModal.show();
    });
    </script>
<?php endif; ?>

    <?php include 'app/view/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
