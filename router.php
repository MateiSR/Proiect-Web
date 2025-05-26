<?php
$request = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];
echo '[DEBUG] requested route: ' . $request;
echo '<br>';
echo '[DEBUG] request method: ' . $request_method;
echo '<br>';
switch ($request) {
    case '/':
        require __DIR__ . '/public/home.php';
        break;
    case '/login':
        require_once __DIR__ . '/controllers/LoginController.php';
        $controller = new LoginController();
        if ($request_method === 'POST') {
            $controller->login();
        } else {
            $controller->index();
        }
        break;
    case '/register':
        require_once __DIR__ . '/controllers/RegisterController.php';
        $controller = new RegisterController();
        if ($request_method === 'POST') {
            $controller->register();
        } else {
            $controller->index();
        }
        break;
    case '/logout':
        require_once __DIR__ . '/controllers/LogoutController.php';
        $controller = new LogoutController();
        $controller->logout();
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/public/404.php';
        break;
}
?>