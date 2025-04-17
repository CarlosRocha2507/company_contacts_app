<?php
// Verify if is not AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Add headers to request if this is not a ajax request
if (!$isAjax){
    include __DIR__ . '/private/components/header.php';
    include __DIR__ . '/private/components/loading.html';
}

$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];
$base_url = '';
ob_start();
switch($request){
    case $base_url:
        require __DIR__ . '/public/views/login.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/public/views/404.php';
        break;
}
ob_flush();