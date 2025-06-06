<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/config/utils.php';

$currentUser = Utils::getLoggedInUser();

if (strpos($request, '/admin') === 0) {
    if (!$currentUser || !isset($currentUser['is_admin']) || !$currentUser['is_admin']) {
        if (!$currentUser) {
            header("Location: /");
        } else {
            http_response_code(403); // Forbidden
            $errorMessage = "Access Denied. You are not an admin.";
            ob_start();
            require __DIR__ . '/views/error_view.php';
            $content = ob_get_clean();
            ob_end_clean();
            require __DIR__ . '/layout.php';
            exit;
        }
        exit;
    }
}



switch ($request) {
    case '/':
        ob_start();
        require __DIR__ . '/views/home.php';
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

    case '/review/add':
        if ($request_method === 'POST') {
            require_once __DIR__ . '/controllers/ReviewController.php';
            $controller = new ReviewController();
            $controller->add();
        } else {
            http_response_code(405);
            $errorMessage = "Method Not Allowed.";
            ob_start();
            require __DIR__ . '/views/error_view.php';
            $content = ob_get_clean();
            ob_end_clean();
        }
        exit;

    case '/admin':
    case '/admin/':
        ob_start();
        require_once __DIR__ . '/views/admin_home_view.php';
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/admin/books':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        $controller->adminIndex();
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/admin/books/edit':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) && ((int) $_GET['id']) > 0) {
            if ($request_method === 'POST') {
                $controller->update((int) $_GET['id']);
            } else {
                $controller->edit((int) $_GET['id']);
            }
        } else {
            http_response_code(400);
            $errorMessage = "Invalid or missing book ID.";
            require __DIR__ . '/views/error_view.php';
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/admin/books/delete':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST' && isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) && ((int) $_POST['id']) > 0) {
            $controller->delete((int) $_POST['id']);
        } else {
            http_response_code(400);
            $errorMessage = "Invalid or missing book ID for deletion.";
            require __DIR__ . '/views/error_view.php';
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/admin/add-book':
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

    case '/admin/import-books':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        $controller->importForm();
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/admin/import-books/csv':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST') {
            $controller->importCsv();
        } else {
            http_response_code(405);
            $errorMessage = "Method Not Allowed.";
            require __DIR__ . '/views/error_view.php';
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/admin/import-books/json':
        ob_start();
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST') {
            $controller->importJson();
        } else {
            http_response_code(405);
            $errorMessage = "Method Not Allowed.";
            require __DIR__ . '/views/error_view.php';
        }
        $content = ob_get_clean();
        ob_end_clean();
        break;

    case '/libraries':
        ob_start();
        require_once __DIR__ . '/controllers/LibraryController.php';
        $controller = new LibraryController();
        if ($request_method === 'GET') {
            $controller->findNearby();
        } else {
            http_response_code(405); // Method Not Allowed
            exit;
        }

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