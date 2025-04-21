<?php

class DepartmentService{
    public static function getDepartments(){
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/table.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $departments = $db->getDepartments();
        $header_button = '<button class="card-header-icon js-modal-trigger" data-target="department_modal" aria-label="more options" onclick="createModal();">
        <span class="icon"><i class="fa-solid fa-plus"></i></span>
        </button>';
        return generateTaleWithFilter("Departments", null, $header_button, self::getHeaders(), self::getRows($departments));
    }
    public static function createDepartment($department_name){
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->createDepartment($department_name);
    }
    public static function updateDepartment($department_id, $department_name){
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->updateDepartment($department_id, $department_name);
    }
    public static function deleteDepartment($department_id){
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->deleteDepartment($department_id);
    }
    public static function getDepartmentModal(){
        require_once __DIR__ . '/../components/modal.php';
        return generateModal("department_modal", "Department", self::getDepartmentForm(), self::getDepartmentButtons());
    }
    private static function getDepartmentForm(){
        return "<form id='department_form' method='POST' action=''>
        <div class='field'>
            <label class='label'>Department Name</label>
            <div class='control'>
                <input class='input' type='text' name='department_name' id='department_name' placeholder='Department Name'>
            </div>
        </div>
        </form>";
    }
    private static function getDepartmentButtons(){
        return "<button class='button is-success' id='department_save' onclick='createDepartment();'>Confirm</button>
        <button class='button is-danger' id='department_cancel'>Cancel</button>";
    }
    private static function getRows($data){
        $rows = "";
        foreach ($data as $row) {
            $rows .= "<tr>";
            $rows .= "<td>" . $row['department'] . "</td>";
            $rows .= "<td>
            <button class='button is-small is-info js-modal-trigger' data-target='department_modal' aria-label='more options' onclick='updateModal({$row["department_id"]}, \"". $row["department"] . "\");'><span class='icon'><i class='fa-solid fa-pen-to-square'></i></span></button>
            <button class='button is-small is-danger'><span class='icon is-small' onclick='deleteDepartment({$row["department_id"]})'><i class='fa-solid fa-trash'></i></span></button>
            </td>";
            $rows .= "</tr>";
        }
        return $rows;
    }   
    private static function getHeaders(){
        return "<tr><th>Department Name</th><th></th></tr>";
    }
}