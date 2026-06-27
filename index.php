<?php
// Set timezone dan mulai session pengguna.
date_default_timezone_set('Asia/Jakarta');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// nambah koemntar coba pull and push

// Parameter router global.
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        require_once 'app/controller/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'login':
        require_once 'app/controller/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        require_once 'app/controller/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        require_once 'app/controller/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'booking':
        require_once 'app/controller/BookingController.php';
        $controller = new BookingController();
        $controller->index();
        break;

    case 'catalog':
        require_once 'app/controller/CatalogController.php';
        $controller = new CatalogController();
        $controller->index();
        break;

    case 'profile':
        require_once 'app/controller/ProfileController.php';
        $controller = new ProfileController();
        $controller->index();
        break;

    case 'check_availability':
        require_once 'app/controller/BookingController.php';
        $controller = new BookingController();
        $controller->checkAvailability();
        break;

    default:
        require_once 'app/controller/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
}
?>