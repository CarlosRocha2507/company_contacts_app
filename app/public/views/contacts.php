<?php
$department_code = 0;
$department_id = 0;
if (isset($_GET["department"])) {
    require __DIR__ . '/../../private/services/DepartmentService.php';
    $department_id = $_GET["department"];
    $department_code = DepartmentService::getDepartmentCodeById($department_id);
}
?>
<div id="gests-contacts">

</div>
<script src="public/js/gests.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    let department_code = "<?php echo $department_code; ?>";
    let department_id = "<?php echo $department_id; ?>";
    if (!isEmpty(department_code) && department_code != 0) {
        gestCodeVerification(department_code, department_id);
    }else{
        console.log("No department code found, loading all contacts.");
        getContactsForGests(null);
    }
</script>