<?php

class DepartmentService
{
    public static function getDepartments()
    {
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
    public static function createDepartment($department_name, $department_extension)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $department_id = $db->createDepartment($department_name);
        if ($department_id) {
            if (empty($department_extension) || $department_extension == null) {
                return true;
            }
            foreach ($department_extension as $extension) {
                if (!$db->createDepartmentExtension($department_id, $extension))
                    return false;
            }
            return true;
        }
        return false;
    }
    public static function updateDepartment($department_id, $department_name, $department_extension)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        if ($db->updateDepartment($department_id, $department_name)) {
            if (empty($department_extension) || $department_extension == null) {
                return true;
            }
            if ($db->updateDepartmentExtencions($department_id, $department_extension)) {
                return true;
            }
            return false;
        }
        return false;
    }
    public static function deleteDepartment($department_id)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->deleteDepartment($department_id);
    }
    public static function getDepartmentModal()
    {
        require_once __DIR__ . '/../components/modal.php';
        return generateModal("department_modal", "Department", self::getDepartmentForm(), self::getDepartmentButtons());
    }
    public static function getDepartmentExtencionLikeSelect($department_id, $selected){
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/select.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $data = $db->getDepartmentExtencions($department_id);
        $options = self::getOptions($data, "extension", "extension", "Choose extension", $selected);
        $icon = "<span class='icon is-small'><i class='fa-solid fa-building'></i></span>";
        $select = generateSelect("department_extension", $options, $icon);
        return $select;
    }
    public static function getDepartmentsLikeSelect()
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/select.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $data = $db->getDepartments();
        $options = self::getOptions($data, "department_id", "department", "Choose department", 0);
        $icon = "<span class='icon is-small'><i class='fa-solid fa-building'></i></span>";
        $select = generateSelect("department_id", $options, $icon);
        return $select;
    }
    private static function getOptions($data, $value, $text, $selectedText, $selectedOption)
    {
        if (empty($data) || $data == null) {
            return "<option value='0' selected disabled>No data found!</option>";
        }
        $options = "<option value='0' selected disabled>$selectedText</option>";
        foreach ($data as $row) {
            if ($row[$value] == $selectedOption) {
                $options .= "<option value='" . $row[$value] . "' selected>" . $row[$text] . "</option>";
                continue;
            }   
            $options .= "<option value='" . $row[$value] . "'>" . $row[$text] . "</option>";
        }
        return $options;
    }
    private static function getDepartmentForm()
    {
        return "<form id='department_form' method='POST' action=''>
        <div class='field'>
            <label class='label'>Department Name</label>
            <div class='control'>
                <input class='input' type='text' name='department_name' id='department_name' placeholder='Department Name'>
            </div>
        </div>
        <div class='control is-fullwidth has-icons-left has-icons-right mb-2'>
            <label for='' class='label'>Extension</label>
                <div class='field has-addons'>
                    <div class='control has-icons-left' style='width: 100%;'>
                            <div class='control'>
                                <input class='input' type='number' name='department_extension' id='department_extension' placeholder='Department extension'>
                            </div>
                            <span class='icon is-small is-left'>
                                <i class='fa fa-lock'></i>
                            </span>
                    </div>
                    <div class='control' onclick='setExtensionList();' style='width: 50px;'>
                        <a class='button is-info' style='height: 100%;'>
                            <i id='password_icon_field' class='fa-solid fa-plus'></i>
                        </a>
                    </div>
                    </div>
                </div>
                <div class='field'>
                    <label class='label'>Extensions List</label>
                    <div class='control'>
                        <ul class='list is-hoverable' id='extensions_list'>
                            
                        </ul>
                    </div>
                </div>
        </form>";
    }
    private static function getDepartmentButtons()
    {
        return "<button class='button is-success' id='department_save' onclick='createDepartment();'>Confirm</button>
        <button class='button is-danger' id='department_cancel'>Cancel</button>";
    }
    private static function getRows($data)
    {
        if (empty($data) || $data == null) {
            return "<tr><td colspan='3'>No data found</td></tr>";
        }
        $rows = "";
        foreach ($data as $row) {
            $rows .= "<tr>";
            $rows .= "<td>" . $row['department'] . "</td>";
            $rows .= "<td>" . $row['extensions'] . "</td>";
            $rows .= "<td>
            <button class='button is-small is-info js-modal-trigger' data-target='department_modal' aria-label='more options' onclick='updateModal({$row["department_id"]}, \"" . $row["department"] . "\", \"" . $row["extensions"] . "\");'><span class='icon'><i class='fa-solid fa-pen-to-square'></i></span></button>
            <button class='button is-small is-danger'><span class='icon is-small' onclick='deleteDepartment({$row["department_id"]})'><i class='fa-solid fa-trash'></i></span></button>
            </td>";
            $rows .= "</tr>";
        }
        return $rows;
    }
    private static function getHeaders()
    {
        return "<tr><th>Department Name</th><th>Extensions</th><th></th></tr>";
    }
}