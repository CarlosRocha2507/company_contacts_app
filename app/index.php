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
                }else{
                    header('Location: /?status=error');
                    exit();
                }
            }
        }
        header('Location: /');
        exit();
    case 'logout':
        include_once __DIR__ . '/private/services/AuthService.php';
        AuthService::logout();
        header('Location: /');
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
    case 'create-contact':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/ContactsService.php';
            if (isset($_POST['department_id']) && isset($_POST["extension"]) && isset($_POST['country_number']) && isset($_POST['contact']) && isset($_POST['contact_email'])) {
                $create = ContactsService::createContact($_POST['country_number'], $_POST['contact'], $_POST['contact_email'], $_POST['department_id'], $_POST["extension"]);
                if ($create) {
                    echo json_encode(array("status" => "success", "message" => "Contact created successfully."));
                    exit();
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error creating contact."));
                    exit();
                }
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    case 'update-contact':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/ContactsService.php';
            if (isset($_POST['department_id']) && isset($_POST['country_number']) && isset($_POST['contact']) && isset($_POST['contact_email']) && isset($_POST['contact_id'])) {
                $update = ContactsService::updateContact($_POST['contact_id'], $_POST['country_number'], $_POST['contact'], $_POST['contact_email'], $_POST['department_id']);
                if ($update) {
                    echo json_encode(array("status" => "success", "message" => "Contact updated successfully."));
                    exit();
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error updating contact."));
                    exit();
                }
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    case 'delete-contact':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/ContactsService.php';
            if (isset($_POST['contact_id'])) {
                $delete = ContactsService::deleteContact($_POST['contact_id']);
                if ($delete) {
                    echo json_encode(array("status" => "success", "message" => "Contact deleted successfully."));
                    exit();
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error deleting contact."));
                    exit();
                }
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    case 'department':
        require __DIR__ . '/public/views/department.php';
        break;
    case 'create-department':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/DepartmentService.php';
            if (isset($_POST['department_name']) && isset($_POST['department_extensions'])) {
                $create = DepartmentService::createDepartment($_POST['department_name'], $_POST['department_extensions']);
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
            if (isset($_POST['department_id']) && isset($_POST['department_name']) && isset($_POST['department_extensions'])) {
                $update = DepartmentService::updateDepartment($_POST['department_id'], $_POST['department_name'], $_POST['department_extensions']);
                if ($update) {
                    echo json_encode(array("status" => "success", "message" => "Department updated successfully."));
                    exit();
                } else {
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
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error deleting department."));
                    exit();
                }
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    case 'get-department-extensions':
        if ($method == 'POST') {
            include_once __DIR__ . '/private/services/DepartmentService.php';
            if (isset($_POST['department_id']) && isset($_POST['selected'])) {
                $extensions = DepartmentService::getDepartmentExtencionLikeSelect($_POST['department_id'], $_POST['selected']);
                echo json_encode(array("status" => "success", "data" => $extensions));
                exit();
            }
        }
        echo json_encode(array("status" => "error", "message" => "Invalid request."));
        exit();
    case 'contacts':
        require __DIR__ . '/public/views/contacts.php';
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