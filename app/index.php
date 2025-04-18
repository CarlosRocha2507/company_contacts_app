<?php
// Verify if is not AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
ob_start();
// Add headers to request if this is not a ajax request
if (!$isAjax) {
    include __DIR__ . '/private/components/header.php';
    include __DIR__ . '/private/components/loading.html';
}

$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];
$base_url = '';

switch ($request) {
    case $base_url:
        require __DIR__ . '/public/views/login.php';
        break;
    case 'login':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/AuthService.php';
            if (isset($_POST['user_name']) && isset($_POST['user_password'])) {
                $user_name = $_POST['user_name'];
                $user_password = $_POST['user_password'];
                $login = AuthService::login($user_name, $user_password);
                if ($login) {
                    header('Location: /dashboard');
                    exit();
                }
            }
        }
        header('Location: /');
        exit();
    case 'logout':
        break;
    case 'register':
        require __DIR__ . '/public/views/register.php';
        break;
    case 'register_user':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/AuthService.php';
            if (isset($_POST['name']) && isset($_POST['user_name']) && isset($_POST['user_password']) && isset($_POST['secret_code'])) {
                $name = $_POST['name'];
                $user_name = $_POST['user_name'];
                $user_password = $_POST['user_password'];
                $secret_code = $_POST['secret_code'];
                $register = AuthService::createAppUser($name, $user_name, $user_password, $secret_code);
                if ($register) {
                    header('Location: /register?status=success');
                    exit();
                }
            }
        }
        header('Location: /register?status=error');
        exit();
    case 'dashboard':
        break;
    default:
        echo "aqui";
        http_response_code(404);
        require __DIR__ . '/public/views/404.php';
        break;
}
ob_end_flush();

if (!$isAjax) {
    include __DIR__ . '/private/components/footer.php';
}