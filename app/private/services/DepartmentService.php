<?php

class DepartmentService
{
    /**
     * Retrieves a list of all departments.
     *
     * This method fetches and returns an array of department data
     * from the database or other data source.
     *
     * @return string HTML string representing the department table with filter options.
     */
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
    /**
     * Retrieves the department code based on the given department ID.
     *
     * @param int $department_id The unique identifier of the department.
     * @return string|null The code of the department if found, or null if not found.
     */
    public static function getDepartmentCodeById($department_id){
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->getDepartmentCodeById($department_id);
    }
    /**
     * Creates a new department with the specified details.
     *
     * @param string $department_name The name of the department.
     * @param string $department_code The unique code identifying the department.
     * @param array $department_extension The phone extensions associated with the department.
     * @return bool True if the department was created successfully, false otherwise.
     */
    public static function createDepartment($department_name, $department_code, $department_extension)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $department_id = $db->createDepartment($department_name, $department_code);
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
    /**
     * Updates the details of a department.
     *
     * @param int $department_id The unique identifier of the department to update.
     * @param string $department_name The new name of the department.
     * @param string $department_code The new code of the department.
     * @param array $department_extensions The new extension number of the department.
     *
     * @return bool True if the department was updated successfully, false otherwise.
     */
    public static function updateDepartment($department_id, $department_name, $department_code, $department_extension)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        if ($db->updateDepartment($department_id, $department_name, $department_code)) {
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
    /**
     * Deletes a department based on the provided department ID.
     *
     * @param int $department_id The unique identifier of the department to be deleted.
     * @return bool True if the department was deleted successfully, false otherwise.
     */
    public static function deleteDepartment($department_id)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->deleteDepartment($department_id);
    }
    /**
     * Retrieves the modal for creating or updating departments.
     *
     * This method is responsible for generating or returning the modal
     * interface used to create a new department or update an existing one.
     *
     * @return mixed The modal content or structure for department operations.
     */
    public static function getDepartmentModal()
    {
        require_once __DIR__ . '/../components/modal.php';
        return generateModal("department_modal", "Department", self::getDepartmentForm(), self::getDepartmentButtons());
    }
    /**
     * Retrieves a list of department extensions formatted for a select input.
     *
     * @param int $department_id The ID of the department to retrieve extensions for.
     * @param mixed $selected The value of the currently selected extension, if any.
     * 
     * @return string HTML string representing the select input for department extensions.
     */
    public static function getDepartmentExtencionLikeSelect($department_id, $selected)
    {
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/select.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $data = $db->getDepartmentExtencions($department_id);
        $options = self::getOptions($data, "extension", "extension", "Choose extension", $selected);
        $icon = "<span class='icon is-small'><i class='fa-solid fa-phone'></i></span>";
        $select = generateSelect("department_extension", $options, $icon);
        return $select;
    }
    /**
     * Retrieves a list of departments formatted for use in a select dropdown.
     *
     * This method fetches department data and formats it into a structure
     * suitable for populating an HTML select element. The returned data
     * typically includes department identifiers and their corresponding names.
     *
     * @return string HTML string representing the select input for departments.
     */
    public static function getDepartmentsLikeSelect($select_id)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/select.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $data = $db->getDepartments();
        $options = self::getOptions($data, "department_id", "department", "Choose department", 0);
        $icon = "<span class='icon is-small'><i class='fa-solid fa-building'></i></span>";
        $select = generateSelect($select_id, $options, $icon);
        return $select;
    }
    /**
     * Generates an array of options for a dropdown or select element.
     *
     * @param array $data An array of data to be used for generating the options.
     * @param string $value The key or property name to be used as the value for each option.
     * @param string $text The key or property name to be used as the display text for each option.
     * @param string $selectedText The text to be displayed for the selected option.
     * @param mixed $selectedOption The value of the option that should be marked as selected.
     * @return string HTML string representing the options for a select element.
     */
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
    /**
     * Retrieves the form structure or configuration for a department.
     *
     * This method is responsible for providing the necessary details
     * or structure required to render or process a department form.
     *
     * @return mixed The form structure or configuration for a department.
     */
    private static function getDepartmentForm()
    {
        return "<form id='department_form' method='POST' action=''>
        <div class='field'>
            <label class='label'>Department Name</label>
            <div class='control has-icons-left'>
                <input class='input' type='text' name='department_name' id='department_name' placeholder='Department Name'>
                <span class='icon is-small is-left'>
                    <i class='fa-solid fa-building-user'></i>
                </span>
            </div>
        </div>
        <div class='field'>
            <label class='label'>Department Code</label>
            <div class='control has-icons-left'>
                <input class='input' type='number' name='department_code' id='department_code' placeholder='Department Code'>
                <span class='icon is-small is-left'>
                    <i class='fa-solid fa-phone'></i>
                </span>
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
    /**
     * Generates and returns the department buttons.
     *
     * This private static method is responsible for creating and returning
     * the buttons associated with departments. The exact implementation
     * details and the structure of the returned buttons depend on the
     * internal logic of the method.
     *
     * @return string HTML string representing the department buttons.
     */
    private static function getDepartmentButtons()
    {
        return "<button class='button is-success' id='department_save' onclick='createDepartment();'>Confirm</button>
        <button class='button is-danger' id='department_cancel'>Cancel</button>";
    }
    /**
     * Retrieves rows from the provided data.
     *
     * @param mixed $data The input data from which rows will be retrieved.
     * @return mixed The rows extracted from the provided data.
     */
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
            $rows .= "<td>" . $row['department_code'] . "</td>";
            $rows .= "<td><button class='button is-small is-success'><span class='icon is-small' onclick='copyToClipboard(\"http://localhost/contacts?department=" . $row["department_id"] . "\");'><i class='fa-solid fa-clipboard'></i></span></button></td>";
            $rows .= "<td>
            <button class='button is-small is-info js-modal-trigger' data-target='department_modal' aria-label='more options' onclick='updateModal({$row["department_id"]}, \"" . $row["department"] . "\", {$row["department_code"]}, \"" . $row["extensions"] . "\");'><span class='icon'><i class='fa-solid fa-pen-to-square'></i></span></button>
            <button class='button is-small is-danger'><span class='icon is-small' onclick='deleteDepartment({$row["department_id"]})'><i class='fa-solid fa-trash'></i></span></button>
            </td>";
            $rows .= "</tr>";
        }
        return $rows;
    }
    /**
     * Retrieves the headers required for the service.
     * 
     * This method is used to obtain the necessary headers for making
     * requests or processing data within the DepartmentService.
     * 
     * @return string HTML string representing the headers for the department table.
     */
    private static function getHeaders()
    {
        return "<tr><th>Department Name</th><th>Extensions</th><th>Code</th><th>Copy department pages</th><th></th></tr>";
    }
}