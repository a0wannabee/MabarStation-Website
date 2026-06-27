<?php
class HomeController {
    private $conn;

    public function __construct() {
        require __DIR__ . '/../../config.php';

        if (!isset($conn) || !$conn) {
            die("Database connection not available.");
        }

        $this->conn = $conn;
    }

    public function index() {
        $reviews = [];
        $res_reviews = mysqli_query($this->conn, "SELECT visitor_name, playstation_type, room_type, rating, review_text FROM reviews WHERE is_active = 1 ORDER BY id DESC LIMIT 9");
        if ($res_reviews) {
            while ($row = mysqli_fetch_assoc($res_reviews)) {
                $reviews[] = $row;
            }
        }

        $page_title = "Home - MabarStation";
        require_once __DIR__ . '/../view/home/home.php';
    }
}
?>
