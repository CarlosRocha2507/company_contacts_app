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
        require __DIR__ . '/public/views/dashboard.php';
        break;
    case 'department':
        require __DIR__ . '/public/views/department.php';
        break;
    case 'create-department':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/DepartmentService.php';
            if (isset($_POST['department_name'])) {
                $department_name = $_POST['department_name'];
                $create = DepartmentService::createDepartment($department_name);
                if ($create) {
                    echo json_encode(array("status" => "success", "message" => "Department created successfully."));
                    exit();
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error creating department."));
                    exit();
                }
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    case 'update-department':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/DepartmentService.php';
            if (isset($_POST['department_id']) && isset($_POST['department_name'])) {
                $department_id = $_POST['department_id'];
                $department_name = $_POST['department_name'];
                $update = DepartmentService::updateDepartment($department_id, $department_name);
                if ($update) {
                    echo json_encode(array("status" => "success", "message" => "Department updated successfully."));
                    exit();
                }else{
                    echo json_encode(array("status" => "error", "message" => "Error updating department."));
                    exit();
                }
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    case 'delete-department':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/DepartmentService.php';
            if (isset($_POST['department_id'])) {
                $department_id = $_POST['department_id'];
                $delete = DepartmentService::deleteDepartment($department_id);
                if ($delete) {
                    echo json_encode(array("status" => "success", "message" => "Department deleted successfully."));
                    exit();
                }else{
                    echo json_encode(array("status" => "error", "message" => "Error deleting department."));
                    exit();
                }
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    default:
        http_response_code(404);
        require __DIR__ . '/public/views/404.php';
        break;
}
ob_end_flush();

if (!$isAjax) {
    include __DIR__ . '/private/components/footer.php';
}