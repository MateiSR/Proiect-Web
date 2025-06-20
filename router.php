<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/config/utils.php';
require_once __DIR__ . '/config/Template.php';

$currentUser = Utils::getLoggedInUser();

if (strpos($request, '/admin') === 0) {
    if (!$currentUser || !isset($currentUser['is_admin']) || !$currentUser['is_admin']) {
        if (!$currentUser) {
            header("Location: /");
        } else {
            http_response_code(403); // Forbidden
            $errorMessage = "Access Denied. You are not an admin.";
            $template = new Template('views/error_view.php');
            $template->errorMessage = $errorMessage;
            $content = $template->render();
        }
        $loggedInUser = Utils::getLoggedInUser();
        $layout = new Template('layout.tpl');
        $layout->loggedInUser = $loggedInUser;
        $layout->content = $content ?? '';
        echo $layout->render();
        exit;
    }
}

$content = '';

switch ($request) {
    case '/':
        require_once __DIR__ . '/controllers/HomeController.php';
        $controller = new HomeController();
        $content = $controller->index();
        break;

    case '/login':
        require_once __DIR__ . '/controllers/LoginController.php';
        $controller = new LoginController();
        if ($request_method === 'POST') {
            $content = $controller->login();
        } else {
            $content = $controller->index();
        }
        break;

    case '/register':
        require_once __DIR__ . '/controllers/RegisterController.php';
        $controller = new RegisterController();
        if ($request_method === 'POST') {
            $content = $controller->register();
        } else {
            $content = $controller->index();
        }
        break;

    case '/logout':
        require_once __DIR__ . '/controllers/LogoutController.php';
        $controller = new LogoutController();
        $controller->logout();
        break;

    case '/books':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        $content = $controller->index();
        break;

    case '/book':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) && ((int) $_GET['id']) > 0) {
            $content = $controller->show((int) $_GET['id']);
        } else {
            http_response_code(400);
            $template = new Template('views/error_view.php');
            $template->errorMessage = "Invalid or missing book ID.";
            $content = $template->render();
        }
        break;

    case '/review/add':
        if ($request_method === 'POST') {
            require_once __DIR__ . '/controllers/ReviewController.php';
            $controller = new ReviewController();
            $controller->add();
        } else {
            http_response_code(405);
            $template = new Template('views/error_view.php');
            $template->errorMessage = "Method Not Allowed.";
            $content = $template->render();
        }
        break;

    case '/groups':
        require_once __DIR__ . '/controllers/GroupController.php';
        $controller = new GroupController();
        $content = $controller->index();
        break;

    case '/group':
        require_once __DIR__ . '/controllers/GroupController.php';
        $controller = new GroupController();
        if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) && ((int) $_GET['id']) > 0) {
            $content = $controller->show((int) $_GET['id']);
        } else {
            http_response_code(400);
            $template = new Template('views/error_view.php');
            $template->errorMessage = "Invalid or missing group ID.";
            $content = $template->render();
        }
        break;

    case '/group/create':
        require_once __DIR__ . '/controllers/GroupController.php';
        $controller = new GroupController();
        if ($request_method === 'POST') {
            $content = $controller->store();
        } else {
            $content = $controller->create();
        }
        break;

    case '/group/join':
        if ($request_method === 'POST') {
            require_once __DIR__ . '/controllers/GroupController.php';
            $controller = new GroupController();
            $controller->join();
        }
        break;

    case '/group/add-book':
        if ($request_method === 'POST') {
            require_once __DIR__ . '/controllers/GroupController.php';
            $controller = new GroupController();
            $controller->addBook();
        }
        break;

    case '/group/discuss':
        if ($request_method === 'POST') {
            require_once __DIR__ . '/controllers/GroupController.php';
            $controller = new GroupController();
            $controller->postDiscussion();
        }
        break;

    case '/admin':
    case '/admin/':
        $template = new Template('views/admin_home_view.php');
        $content = $template->render();
        break;

    case '/admin/books':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        $content = $controller->adminIndex();
        break;

    case '/admin/books/edit':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) && ((int) $_GET['id']) > 0) {
            if ($request_method === 'POST') {
                $content = $controller->update((int) $_GET['id']);
            } else {
                $content = $controller->edit((int) $_GET['id']);
            }
        } else {
            http_response_code(400);
            $template = new Template('views/error_view.php');
            $template->errorMessage = "Invalid or missing book ID.";
            $content = $template->render();
        }
        break;

    case '/admin/books/delete':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST' && isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) && ((int) $_POST['id']) > 0) {
            $content = $controller->delete((int) $_POST['id']);
        } else {
            http_response_code(400);
            $template = new Template('views/error_view.php');
            $template->errorMessage = "Invalid or missing book ID for deletion.";
            $content = $template->render();
        }
        break;

    case '/admin/add-book':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST') {
            $content = $controller->store();
        } else {
            $content = $controller->create();
        }
        break;

    case '/admin/import-books':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        $content = $controller->importForm();
        break;

    case '/admin/import-books/csv':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST') {
            $content = $controller->importCsv();
        } else {
            http_response_code(405);
            $template = new Template('views/error_view.php');
            $template->errorMessage = "Method Not Allowed.";
            $content = $template->render();
        }
        break;

    case '/admin/import-books/json':
        require_once __DIR__ . '/controllers/BookController.php';
        $controller = new BookController();
        if ($request_method === 'POST') {
            $content = $controller->importJson();
        } else {
            http_response_code(405);
            $template = new Template('views/error_view.php');
            $template->errorMessage = "Method Not Allowed.";
            $content = $template->render();
        }
        break;

    case '/rss':
        require_once __DIR__ . '/controllers/RssController.php';
        $controller = new RssController();
        $controller->generateFeed();
        break;

    case '/statistics':
        require_once __DIR__ . '/controllers/StatisticsController.php';
        $controller = new StatisticsController();
        $content = $controller->index();
        break;

    case '/statistics/export/csv':
        require_once __DIR__ . '/controllers/StatisticsController.php';
        $controller = new StatisticsController();
        $controller->exportCsv();
        break;

    case '/statistics/export/docbook':
        require_once __DIR__ . '/controllers/StatisticsController.php';
        $controller = new StatisticsController();
        $controller->exportDocbook();
        break;

    case '/libraries':
        require_once __DIR__ . '/controllers/LibraryController.php';
        $controller = new LibraryController();
        if ($request_method === 'GET') {
            $controller->findNearby();
        } else {
            http_response_code(405); // Method Not Allowed
            exit;
        }
        break;

    case '/docs':
        $content = file_get_contents(__DIR__ . '/public/docs.html');
        break;


    default:
        http_response_code(404);
        $template = new Template('public/404.php');
        $content = $template->render();
        break;
}

$loggedInUser = Utils::getLoggedInUser();
$layout = new Template('layout.tpl');
$layout->loggedInUser = $loggedInUser;
$layout->content = $content;
echo $layout->render();