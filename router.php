<?php
$request = $_SERVER['REQUEST_URI'];
echo 'requested route: '.$request;
echo '<br>';
switch ($request) {
    case '/':
        require __DIR__.'/views/home.php';
        break;
    case '/test':
        require __DIR__.'/views/test.php';
        break;
    default:
        http_response_code(404);
        require __DIR__.'/views/404.php';
        break;
}
?>