<?php
require __DIR__ . '/../../private/services/DepartmentService.php';
require_once __DIR__ . '/../../private/helpers/HSession.php';
HSession::startSession();
if (!HSession::isLoggedIn()) {
    header("Location: /login.php");
    exit();
}
echo DepartmentService::getDepartments();
echo DepartmentService::getDepartmentModal();
?>
<script src="public/js/department.js"></script>
