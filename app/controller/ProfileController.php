<?php

class ProfileController {
    private $conn;

    public function __construct() {
        require __DIR__ . '/../../config.php';

        if (!isset($conn) || !$conn) {
            die("Database connection not available.");
        }

        $this->conn = $conn;
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Kamu harus login terlebih dahulu.";
            header("Location: index.php?page=login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $error = '';
        $success = '';

        // Handle photo upload
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
            $file = $_FILES['profile_picture'];

            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
            } elseif ($file['error'] !== UPLOAD_ERR_OK) {
                error_log("Upload error code: " . $file['error']);
                $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
            } else {
                $filename = $file['name'];
                $filesize = $file['size'];
                $tmp_path = $file['tmp_name'];

                // Validate size (max 2MB = 2097152 bytes)
                $max_size = 2 * 1024 * 1024;
                if ($filesize > $max_size) {
                    error_log("Upload size limit exceeded: " . $filesize);
                    $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
                } else {
                    // Validate extension
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if (!in_array($extension, $allowed_extensions)) {
                        error_log("Upload invalid extension: " . $extension);
                        $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
                    } else {
                        // Validate MIME type
                        $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
                        $mime_type = mime_content_type($tmp_path);

                        if (!in_array($mime_type, $allowed_mimes)) {
                            error_log("Upload invalid MIME type: " . $mime_type);
                            $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
                        } else {
                            $new_filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                            $upload_dir = 'uploads/';
                            $dest_path = $upload_dir . $new_filename;

                            if (!file_exists($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }

                            if (move_uploaded_file($tmp_path, $dest_path)) {
                                // Get old profile picture
                                $stmt = mysqli_prepare($this->conn, "SELECT profile_picture FROM users WHERE id = ?");
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "s", $user_id);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    $old_user = mysqli_fetch_assoc($result);
                                    $old_pic = $old_user['profile_picture'] ?? '';
                                    mysqli_stmt_close($stmt);

                                    // Update in database using secure VARCHAR parameter type 's'
                                    $stmt_up = mysqli_prepare($this->conn, "UPDATE users SET profile_picture = ? WHERE id = ?");
                                    if ($stmt_up) {
                                        mysqli_stmt_bind_param($stmt_up, "ss", $dest_path, $user_id);
                                        
                                        if (mysqli_stmt_execute($stmt_up)) {
                                            $_SESSION['profile_picture'] = $dest_path;

                                            // Delete old profile picture if exists and not default
                                            if (!empty($old_pic) && file_exists($old_pic) && $old_pic !== 'uploads/default.png') {
                                                @unlink($old_pic);
                                            }

                                            $success = "Foto profil berhasil diperbarui.";
                                        } else {
                                            error_log("Upload DB update execution failed: " . mysqli_stmt_error($stmt_up));
                                            $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
                                        }
                                        mysqli_stmt_close($stmt_up);
                                    } else {
                                        error_log("Upload DB update preparation failed: " . mysqli_error($this->conn));
                                        $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
                                    }
                                } else {
                                    error_log("Upload select old picture prep failed: " . mysqli_error($this->conn));
                                    $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
                                }
                            } else {
                                error_log("Upload move_uploaded_file failed");
                                $error = "Foto profil belum berhasil diunggah. Silakan coba lagi.";
                            }
                        }
                    }
                }
            }
        }

        // Fetch current user details with prepared statements
        $stmt = mysqli_prepare($this->conn, "SELECT username, email, phone_number, profile_picture, created_at FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$user) {
            session_destroy();
            header("Location: index.php?page=login");
            exit();
        }

        // Update expired bookings first
        $this->updateCompletedBookings();

        // Fetch user bookings from vw_booking_detail view
        $bookings = [];
        $stmtBookings = mysqli_prepare($this->conn, "
            SELECT booking_id, playstation_name, room_name, booking_date, start_time, end_time, duration, total_price, status
            FROM vw_booking_detail
            WHERE user_id = ?
            ORDER BY booking_date DESC, start_time DESC
        ");
        if ($stmtBookings) {
            mysqli_stmt_bind_param($stmtBookings, "s", $user_id);
            mysqli_stmt_execute($stmtBookings);
            $bookingsResult = mysqli_stmt_get_result($stmtBookings);
            while ($row = mysqli_fetch_assoc($bookingsResult)) {
                $bookings[] = $row;
            }
            mysqli_stmt_close($stmtBookings);
        } else {
            error_log("Failed to prepare bookings query in ProfileController: " . mysqli_error($this->conn));
        }

        $page_title = "Profile - MabarStation";
        require_once __DIR__ . '/../view/profile/profile.php';
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
