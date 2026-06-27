<?php

class BookingController {
    private $conn;

    public function __construct() {
        require __DIR__ . '/../../config.php';

        if (!isset($conn) || !$conn) {
            die("Database connection not available.");
        }

        $this->conn = $conn;
    }

    public function index() {
        $this->updateCompletedBookings();

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Kamu harus login terlebih dahulu untuk membuat booking.";
            header("Location: index.php?page=login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $currentUser = null;
        $stmt_user = mysqli_prepare($this->conn, "SELECT username, phone_number FROM users WHERE id = ?");
        if ($stmt_user) {
            mysqli_stmt_bind_param($stmt_user, "s", $user_id);
            mysqli_stmt_execute($stmt_user);
            $res_user = mysqli_stmt_get_result($stmt_user);
            $currentUser = mysqli_fetch_assoc($res_user);
            mysqli_stmt_close($stmt_user);
        }

        $error = '';
        $success = '';
        $booking_total_price = 0;

        // Ambil data PlayStation untuk pilihan awal.
        $playstations = [];
        $res_ps = mysqli_query($this->conn, "SELECT id, type, description, price_per_hour, stock, status FROM playstations ORDER BY type ASC");
        if ($res_ps) {
            while ($row = mysqli_fetch_assoc($res_ps)) {
                $playstations[] = $row;
            }
        } else {
            error_log("Booking fetch playstations failed: " . mysqli_error($this->conn));
        }

        // Ambil ketersediaan awal slot room tipe Regular.
        $total_regular = 0;
        $res_reg = mysqli_query($this->conn, "SELECT COUNT(*) AS total FROM rooms WHERE room_type = 'Regular' AND status = 'available'");
        if ($res_reg && $row = mysqli_fetch_assoc($res_reg)) {
            $total_regular = intval($row['total']);
        } else {
            error_log("Booking fetch regular room status failed: " . mysqli_error($this->conn));
        }

        // Ambil ketersediaan awal slot room tipe VIP.
        $total_vip = 0;
        $res_vip = mysqli_query($this->conn, "SELECT COUNT(*) AS total FROM rooms WHERE room_type = 'VIP' AND status = 'available'");
        if ($res_vip && $row = mysqli_fetch_assoc($res_vip)) {
            $total_vip = intval($row['total']);
        } else {
            error_log("Booking fetch vip room status failed: " . mysqli_error($this->conn));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $customer_name = trim($_POST['username'] ?? $_POST['customer_name'] ?? '');
            $phone_number = trim($_POST['phone_number'] ?? '');
            $booking_date = trim($_POST['booking_date'] ?? '');
            $start_time = trim($_POST['start_time'] ?? '');
            $duration_hours = intval($_POST['duration_hours'] ?? 0);
            $playstation_id = trim($_POST['playstation_id'] ?? '');
            $room_type = trim($_POST['room_type'] ?? '');
            $notes = trim($_POST['notes'] ?? '');

            if (empty($phone_number) && isset($currentUser['phone_number'])) {
                $phone_number = $currentUser['phone_number'];
            }
            if (empty($customer_name) && isset($currentUser['username'])) {
                $customer_name = $currentUser['username'];
            }

            // Validasi tanggal dan waktu agar tidak memilih waktu lampau.
            $bookingDateTime = strtotime($booking_date . ' ' . $start_time);
            $now = time();

            if (empty($customer_name) || empty($phone_number) || empty($booking_date) || empty($start_time) || $duration_hours < 1 || empty($playstation_id) || empty($room_type)) {
                $error = "Lengkapi semua data booking terlebih dahulu.";
            } elseif ($bookingDateTime <= $now) {
                $error = "Jadwal yang kamu pilih sudah terisi. Silakan pilih jam atau room lain.";
            } else {
                $new_start = date('H:i:s', strtotime($start_time));
                $new_end = date('H:i:s', strtotime($start_time) + ($duration_hours * 3600));

                // Cari ruangan fisik yang masih kosong pada jadwal tersebut.
                $stmt_find_room = mysqli_prepare($this->conn, "
                    SELECT id FROM rooms 
                    WHERE room_type = ? 
                      AND status = 'available'
                      AND id NOT IN (
                          SELECT room_id FROM bookings
                          WHERE booking_date = ?
                            AND status = 'confirmed'
                            AND start_time < ?
                            AND ADDTIME(start_time, SEC_TO_TIME(duration_hours * 3600)) > ?
                      )
                    LIMIT 1
                ");
                
                if ($stmt_find_room) {
                    mysqli_stmt_bind_param($stmt_find_room, "ssss", $room_type, $booking_date, $new_end, $new_start);
                    mysqli_stmt_execute($stmt_find_room);
                    $res_find_room = mysqli_stmt_get_result($stmt_find_room);
                    $free_room = mysqli_fetch_assoc($res_find_room);
                    mysqli_stmt_close($stmt_find_room);

                    if (!$free_room) {
                        $error = "Jadwal yang kamu pilih sudah terisi. Silakan pilih jam atau room lain.";
                    } else {
                        $room_id = $free_room['id'];
                        // Buat ID Booking unik kustom.
                        $booking_id = generateUniqueId($this->conn, 'bookings', 'ORD');

                        // Panggil stored procedure sp_create_booking.
                        $stmt_call = mysqli_prepare($this->conn, "CALL sp_create_booking(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        if ($stmt_call) {
                            mysqli_stmt_bind_param($stmt_call, "ssssssisss", 
                                $booking_id,
                                $user_id, 
                                $customer_name, 
                                $phone_number, 
                                $booking_date, 
                                $start_time, 
                                $duration_hours, 
                                $playstation_id, 
                                $room_id, 
                                $notes
                            );
                            
                            if (mysqli_stmt_execute($stmt_call)) {
                                $res_price = mysqli_query($this->conn, "SELECT total_price FROM bookings WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 1");
                                if ($res_price && $row_p = mysqli_fetch_assoc($res_price)) {
                                    $booking_total_price = $row_p['total_price'];
                                }
                                $success = "success";
                            } else {
                                error_log("Booking execution failed (SP): " . mysqli_stmt_error($stmt_call));
                                $error = "Booking belum berhasil diproses. Silakan cek kembali data booking kamu.";
                            }
                            mysqli_stmt_close($stmt_call);
                        } else {
                            error_log("Booking prep statement failed: " . mysqli_error($this->conn));
                            $error = "Booking belum berhasil diproses. Silakan cek kembali data booking kamu.";
                        }
                    }
                } else {
                    error_log("Booking find room prep statement failed: " . mysqli_error($this->conn));
                    $error = "Booking belum berhasil diproses. Silakan cek kembali data booking kamu.";
                }
            }
        }

        $page_title = "Booking - MabarStation";
        require_once __DIR__ . '/../view/booking/booking.php';
    }

    public function checkAvailability() {
        $this->updateCompletedBookings();
        header('Content-Type: application/json');

        $date = trim($_GET['date'] ?? '');
        $time = trim($_GET['time'] ?? '');
        $duration = intval($_GET['duration'] ?? 1);

        if (empty($date) || empty($time) || $duration < 1) {
            echo json_encode(['status' => 'error', 'message' => 'Lengkapi semua data booking terlebih dahulu.']);
            exit();
        }

        $start_timestamp = strtotime($time);
        $end_timestamp = $start_timestamp + ($duration * 3600);
        $new_start = date('H:i:s', $start_timestamp);
        $new_end = date('H:i:s', $end_timestamp);

        // 1. Hitung bentrok ruangan (overlap).
        $total_regular = 0;
        $res_reg = mysqli_query($this->conn, "SELECT COUNT(*) AS total FROM rooms WHERE room_type = 'Regular' AND status = 'available'");
        if ($res_reg && $row = mysqli_fetch_assoc($res_reg)) {
            $total_regular = intval($row['total']);
        } else {
            error_log("Check availability regular query failed: " . mysqli_error($this->conn));
        }

        $total_vip = 0;
        $res_vip = mysqli_query($this->conn, "SELECT COUNT(*) AS total FROM rooms WHERE room_type = 'VIP' AND status = 'available'");
        if ($res_vip && $row = mysqli_fetch_assoc($res_vip)) {
            $total_vip = intval($row['total']);
        } else {
            error_log("Check availability VIP query failed: " . mysqli_error($this->conn));
        }

        $regular_overlaps = 0;
        $stmt_reg_o = mysqli_prepare($this->conn, "
            SELECT COUNT(*) AS total
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            WHERE r.room_type = 'Regular'
              AND b.booking_date = ?
              AND b.status = 'confirmed'
              AND b.start_time < ?
              AND ADDTIME(b.start_time, SEC_TO_TIME(b.duration_hours * 3600)) > ?
        ");
        if ($stmt_reg_o) {
            mysqli_stmt_bind_param($stmt_reg_o, "sss", $date, $new_end, $new_start);
            mysqli_stmt_execute($stmt_reg_o);
            $res_reg_o = mysqli_stmt_get_result($stmt_reg_o);
            if ($row = mysqli_fetch_assoc($res_reg_o)) {
                $regular_overlaps = intval($row['total']);
            }
            mysqli_stmt_close($stmt_reg_o);
        } else {
            error_log("Check regular overlap prep statement failed: " . mysqli_error($this->conn));
        }

        $vip_overlaps = 0;
        $stmt_vip_o = mysqli_prepare($this->conn, "
            SELECT COUNT(*) AS total
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            WHERE r.room_type = 'VIP'
              AND b.booking_date = ?
              AND b.status = 'confirmed'
              AND b.start_time < ?
              AND ADDTIME(b.start_time, SEC_TO_TIME(b.duration_hours * 3600)) > ?
        ");
        if ($stmt_vip_o) {
            mysqli_stmt_bind_param($stmt_vip_o, "sss", $date, $new_end, $new_start);
            mysqli_stmt_execute($stmt_vip_o);
            $res_vip_o = mysqli_stmt_get_result($stmt_vip_o);
            if ($row = mysqli_fetch_assoc($res_vip_o)) {
                $vip_overlaps = intval($row['total']);
            }
            mysqli_stmt_close($stmt_vip_o);
        } else {
            error_log("Check VIP overlap prep statement failed: " . mysqli_error($this->conn));
        }

        $regular_remaining = max(0, $total_regular - $regular_overlaps);
        $vip_remaining = max(0, $total_vip - $vip_overlaps);

        $rooms_list = [
            [
                'id' => 'Regular',
                'room_name' => 'Regular Space',
                'room_type' => 'Regular',
                'price_extra' => 0,
                'total_slots' => $total_regular,
                'remaining_slots' => $regular_remaining,
                'is_available' => ($total_regular > 0 && $regular_remaining > 0)
            ],
            [
                'id' => 'VIP',
                'room_name' => 'VIP Lounge',
                'room_type' => 'VIP',
                'price_extra' => 10000,
                'total_slots' => $total_vip,
                'remaining_slots' => $vip_remaining,
                'is_available' => ($total_vip > 0 && $vip_remaining > 0)
            ]
        ];

        // 2. Hitung bentrok PlayStation (overlap).
        $playstations = [];
        $res_ps = mysqli_query($this->conn, "SELECT id, type, description, price_per_hour, stock, status FROM playstations ORDER BY type ASC");
        if ($res_ps) {
            while ($row = mysqli_fetch_assoc($res_ps)) {
                $playstations[] = $row;
            }
        }

        $ps_overlaps = [];
        $stmt_ps = mysqli_prepare($this->conn, "
            SELECT playstation_id, COUNT(*) AS total
            FROM bookings
            WHERE booking_date = ?
              AND status = 'confirmed'
              AND start_time < ?
              AND ADDTIME(start_time, SEC_TO_TIME(duration_hours * 3600)) > ?
            GROUP BY playstation_id
        ");
        if ($stmt_ps) {
            mysqli_stmt_bind_param($stmt_ps, "sss", $date, $new_end, $new_start);
            mysqli_stmt_execute($stmt_ps);
            $res_ps_overlap = mysqli_stmt_get_result($stmt_ps);
            while ($row = mysqli_fetch_assoc($res_ps_overlap)) {
                $ps_overlaps[$row['playstation_id']] = intval($row['total']);
            }
            mysqli_stmt_close($stmt_ps);
        } else {
            error_log("Check PS overlaps prep statement failed: " . mysqli_error($this->conn));
        }

        $ps_list = [];
        foreach ($playstations as $ps) {
            $p_id = $ps['id'];
            $overlap = $ps_overlaps[$p_id] ?? 0;
            $total_stock = intval($ps['stock']);
            $remaining_stock = max(0, $total_stock - $overlap);
            $is_available = ($ps['status'] === 'available' && $remaining_stock > 0);
            $ps_list[] = [
                'id' => $p_id,
                'type' => $ps['type'],
                'description' => $ps['description'],
                'price_per_hour' => intval($ps['price_per_hour']),
                'total_stock' => $total_stock,
                'remaining_stock' => $remaining_stock,
                'is_available' => $is_available
            ];
        }

        echo json_encode([
            'status' => 'success',
            'rooms' => $rooms_list,
            'playstations' => $ps_list
        ]);
        exit();
    }

    private function updateCompletedBookings()
    {
        $stmt = mysqli_prepare($this->conn, "
            UPDATE bookings
            SET status = 'completed'
            WHERE status = 'confirmed'
              AND DATE_ADD(TIMESTAMP(booking_date, start_time), INTERVAL duration_hours HOUR) < NOW()
        ");

        if ($stmt) {
            if (!mysqli_stmt_execute($stmt)) {
                error_log('Failed to execute update completed bookings: ' . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log('Failed to prepare update completed bookings: ' . mysqli_error($this->conn));
        }
    }
}
?>
