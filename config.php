<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "mabarstation_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Terjadi kendala pada sistem. Silakan coba beberapa saat lagi.");
}

if (!function_exists('generateUniqueId')) {
    function generateUniqueId($conn, $table, $prefix) {
        $allowed_tables = ['users', 'bookings', 'reviews', 'games', 'playstations', 'rooms'];
        if (!in_array($table, $allowed_tables)) {
            die("Invalid table name for ID generation.");
        }
        
        do {
            $id = $prefix . '-' . bin2hex(random_bytes(6));
            $stmt = mysqli_prepare($conn, "SELECT id FROM " . $table . " WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $exists = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
        } while ($exists);

        return $id;
    }
}
?>
