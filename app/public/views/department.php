<?php
require __DIR__ . '/../../private/services/DepartmentService.php';
echo DepartmentService::getDepartments();
echo DepartmentService::getDepartmentModal();
?>
<script src="public/js/department.js"></script>
