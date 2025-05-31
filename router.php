<?php
$request = $_SERVER['REQUEST_URI'];
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

    default:
        http_response_code(404);
        ob_start();
        require __DIR__ . '/public/404.php';
        $content = ob_get_clean();
        ob_end_clean();
        break;
}

// global $content to be used in the layout
$loggedInUser = Utils::getLoggedInUser();

require __DIR__ . '/layout.php';

?>