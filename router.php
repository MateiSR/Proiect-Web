<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/config/utils.php';


switch ($request) {
    case '/':
        ob_start();
        require __DIR__ . '/public/home.php';
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/login':
        ob_start();
        require_once __DIR__ . '/controllers/LoginController.php';
        $controller = new LoginController();
        if ($request_method === 'POST') {
            $controller->login();
        } else {
            $controller->index();
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/register':
        ob_start();
        require_once __DIR__ . '/controllers/RegisterController.php';
        $controller = new RegisterController();
        if ($request_method === 'POST') {
            $controller->register();
        } else {
            $controller->index();
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/logout':
        ob_start();
        require_once __DIR__ . '/controllers/LogoutController.php';
        $controller = new LogoutController();
        $controller->logout();
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/books':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        $controller->index();
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/book':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) && ((int) $_GET['id']) > 0) {
            $controller->show((int) $_GET['id']);
        } else {
            http_response_code(400);
            $errorMessage = "Invalid or missing book ID.";
            require __DIR__ . '/views/error_view.php';
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/add-book':
        ob_start();
        $loggedInUser = Utils::getLoggedInUser();
        if (!$loggedInUser) {
            ob_end_clean();
            header("Location: /login");
            exit;
        }
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    default:
        http_response_code(404);
        ob_start();
        require __DIR__ . '/public/404.php';
        $content = ob_get_clean();
        ob_end_clean();
        break;
}

$loggedInUser = Utils::getLoggedInUser();

require __DIR__ . '/layout.php';

?>