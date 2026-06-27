<?php
class CatalogController {
    private $conn;

    public function __construct() {
        require __DIR__ . '/../../config.php';

        if (!isset($conn) || !$conn) {
            die("Database connection not available.");
        }

        $this->conn = $conn;
    }

    public function index() {
        $search = trim($_GET['search'] ?? '');
        $limit = 6;
        $halaman = max(1, (int)($_GET['halaman'] ?? 1));
        $offset = ($halaman - 1) * $limit;
        $keyword = "%" . $search . "%";

        // Count total games matching the keyword
        $stmt_total = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM games WHERE nama_game LIKE ?");
        mysqli_stmt_bind_param($stmt_total, "s", $keyword);
        mysqli_stmt_execute($stmt_total);
        $result_total = mysqli_stmt_get_result($stmt_total);
        $row_total = mysqli_fetch_assoc($result_total);
        $total_game = (int)($row_total['total'] ?? 0);
        $total_halaman = max(1, (int)ceil($total_game / $limit));
        mysqli_stmt_close($stmt_total);

        if ($halaman > $total_halaman) {
            $halaman = $total_halaman;
            $offset = ($halaman - 1) * $limit;
        }

        // Fetch games with prepared statement
        $stmt_games = mysqli_prepare($this->conn, "SELECT id, nama_game, deskripsi, gambar FROM games WHERE nama_game LIKE ? ORDER BY nama_game ASC LIMIT ? OFFSET ?");
        mysqli_stmt_bind_param($stmt_games, "sii", $keyword, $limit, $offset);
        mysqli_stmt_execute($stmt_games);
        $hasil_query = mysqli_stmt_get_result($stmt_games);

        $page_title = "Game Catalog - MabarStation";
        require_once __DIR__ . '/../view/catalog/catalog.php';

        mysqli_stmt_close($stmt_games);
    }
}
?>
