<?php
class AuthController {
    private $conn;

    public function __construct() {
        require __DIR__ . '/../../config.php';

        if (!isset($conn) || !$conn) {
            die("Database connection not available.");
        }

        $this->conn = $conn;
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=home");
            exit();
        }

        $error = '';
        $success = '';

        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Email atau password salah.";
            } else {
                $stmt = mysqli_prepare($this->conn, "SELECT id, username, email, phone_number, password, profile_picture FROM users WHERE email = ?");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $user = mysqli_fetch_assoc($result);
                    mysqli_stmt_close($stmt);

                    if ($user && password_verify($password, $user['password'])) {
                        session_regenerate_id(true);

                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['phone_number'] = $user['phone_number'];
                        $_SESSION['profile_picture'] = $user['profile_picture'];

                        header("Location: index.php?page=home");
                        exit();
                    } else {
                        $error = "Email atau password salah.";
                    }
                } else {
                    error_log("Login prep statement failed: " . mysqli_error($this->conn));
                    $error = "Terjadi kendala pada sistem. Silakan coba beberapa saat lagi.";
                }
            }
        }

        $page_title = "Login - MabarStation";
        require_once __DIR__ . '/../view/auth/login.php';
    }

    public function register() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=home");
            exit();
        }

        $error = '';
        $username = '';
        $email = '';
        $phone_number = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone_number = trim($_POST['phone_number'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($email) || empty($phone_number) || empty($password) || empty($confirm_password)) {
                if (empty($phone_number)) {
                    $error = "Nomor telepon wajib diisi.";
                } else {
                    $error = "Registrasi belum berhasil. Silakan coba lagi.";
                }
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Registrasi belum berhasil. Silakan coba lagi.";
            } elseif (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
                $error = "Nomor telepon harus berupa angka 10 sampai 15 digit.";
            } elseif (strlen($password) < 6) {
                $error = "Registrasi belum berhasil. Silakan coba lagi.";
            } elseif ($password !== $confirm_password) {
                $error = "Registrasi belum berhasil. Silakan coba lagi.";
            } else {
                // Check email uniqueness
                $stmt = mysqli_prepare($this->conn, "SELECT id FROM users WHERE email = ?");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $exists = mysqli_fetch_assoc($result);
                    mysqli_stmt_close($stmt);

                    if ($exists) {
                        $error = "Email sudah terdaftar. Gunakan email lain.";
                    } else {
                        // Check phone number uniqueness
                        $stmt_phone = mysqli_prepare($this->conn, "SELECT id FROM users WHERE phone_number = ?");
                        if ($stmt_phone) {
                            mysqli_stmt_bind_param($stmt_phone, "s", $phone_number);
                            mysqli_stmt_execute($stmt_phone);
                            $result_phone = mysqli_stmt_get_result($stmt_phone);
                            $exists_phone = mysqli_fetch_assoc($result_phone);
                            mysqli_stmt_close($stmt_phone);

                            if ($exists_phone) {
                                $error = "Nomor telepon sudah terdaftar. Gunakan nomor lain.";
                            } else {
                                // Generate Unique Custom USER ID
                                $user_id = generateUniqueId($this->conn, 'users', 'USER');
                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                $default_pic = 'uploads/default.png';

                                $stmt_ins = mysqli_prepare($this->conn, "INSERT INTO users (id, username, email, phone_number, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
                                if ($stmt_ins) {
                                    mysqli_stmt_bind_param($stmt_ins, "ssssss", $user_id, $username, $email, $phone_number, $hashed_password, $default_pic);

                                    if (mysqli_stmt_execute($stmt_ins)) {
                                        $_SESSION['success'] = "Registrasi berhasil! Silakan masuk.";
                                        header("Location: index.php?page=login");
                                        exit();
                                    } else {
                                        error_log("Register DB execute failed: " . mysqli_stmt_error($stmt_ins));
                                        $error = "Registrasi belum berhasil. Silakan coba lagi.";
                                    }
                                    mysqli_stmt_close($stmt_ins);
                                } else {
                                    error_log("Register insert prep statement failed: " . mysqli_error($this->conn));
                                    $error = "Registrasi belum berhasil. Silakan coba lagi.";
                                }
                            }
                        } else {
                            error_log("Register select phone uniqueness prep statement failed: " . mysqli_error($this->conn));
                            $error = "Registrasi belum berhasil. Silakan coba lagi.";
                        }
                    }
                } else {
                    error_log("Register select uniqueness prep statement failed: " . mysqli_error($this->conn));
                    $error = "Registrasi belum berhasil. Silakan coba lagi.";
                }
            }
        }

        $page_title = "Register - MabarStation";
        require_once __DIR__ . '/../view/auth/register.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        session_start();
        $_SESSION['success'] = "Kamu berhasil logout.";
        header("Location: index.php?page=login");
        exit();
    }
}
?>
