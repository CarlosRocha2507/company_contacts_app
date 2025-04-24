<?php

class ContactsService
{
    public static function getContacts()
    {
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/table.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $departments = $db->getContacts();
        $header_button = '<button class="card-header-icon js-modal-trigger" data-target="contacts_modal" aria-label="more options" onclick="createModal();">
        <span class="icon"><i class="fa-solid fa-plus"></i></span>
        </button>';
        return generateTaleWithFilter("Departments", null, $header_button, self::getHeaders(), self::getRows($departments, false));
    }
    public static function getContactsForGests()
    {
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/table.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $departments = $db->getContacts();
        $header_button = '';
        return generateTaleWithFilter("Departments", null, $header_button, self::getHeaders(), self::getRows($departments, true));
    }
    public static function getContactFromDepartment($department_id)
    {
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/table.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $contacts = $db->getContactFromDepartment($department_id);
        return generateTaleWithFilter("Contacts", null, null, self::getHeaders(), self::getRows($contacts));
    }
    public static function getContactModal()
    {
        require_once __DIR__ . '/../components/modal.php';
        return generateModal("contacts_modal", "Contact", self::getContactForm(), self::getContactButtons());
    }
    public static function createContact($contry_number, $contact, $email, $department_id, $extension)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->createContact($contry_number, $contact, $email, $department_id, $extension);
    }
    public static function updateContact($contact_id, $contact_number, $contact, $email, $department_id)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->updateContact($contact_id, $contact_number, $contact, $email, $department_id);
    }
    public static function deleteContact($contact_id)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->deleteContact($contact_id);
    }
    private static function getHeaders()
    {
        return "<th>Department</th>
        <th>Contact</th>
        <th>Extension</th>
        <th>Email</th>
        <th></th>";
    }
    private static function getRows($data, $is_for_gest)
    {
        if (empty($data) || $data == null) {
            return "<tr><td colspan='6'>No contacts found</td></tr>";
        }
        $rows = "";
        if ($is_for_gest) {
            foreach ($data as $row) {
                $rows .= "<tr>";
                $rows .= "<td>" . $row['department'] . "</td>";
                $rows .= "<td>" . $row['contry_number'] . " " . $row['contact'] . "</td>";
                $rows .= "<td>" . $row['extension'] . "</td>";
                $rows .= "<td>" . $row['email'] . "</td>";
                $rows .= "<td></td>";
                $rows .= "</tr>";
            }
            return $rows;
        }
        foreach ($data as $row) {
            $rows .= "<tr>";
            $rows .= "<td>" . $row['department'] . "</td>";
            $rows .= "<td>" . $row['contry_number'] . " " . $row['contact'] . "</td>";
            $rows .= "<td>" . $row['extension'] . "</td>";
            $rows .= "<td>" . $row['email'] . "</td>";
            $rows .= "<td>
            <button class='button is-small is-info js-modal-trigger' data-target='contacts_modal' aria-label='more options' onclick='updateModal({$row["department_id"]}, {$row["contact_id"]}, {$row["contact"]}, \"" . $row["contry_number"] . "\",\"" . $row["email"] . "\", \"" . $row["extension"] . "\");'><span class='icon'><i class='fa-solid fa-pen-to-square'></i></span></button>
            <button class='button is-small is-danger'><span class='icon is-small' onclick='deleteContact({$row["contact_id"]})'><i class='fa-solid fa-trash'></i></span></button>
            </td>";
            $rows .= "</tr>";
        }
        return $rows;
    }
    private static function getContactForm()
    {
        include_once __DIR__ . '/../services/DepartmentService.php';
        include_once __DIR__ . '/../API/Restcountries.php';
        return "<form id='contact_form' method='POST' action=''>
        <div class='field'>
            <label class='label'>Department</label>
            <div class='control'>
               " . DepartmentService::getDepartmentsLikeSelect() . "
            </div>
        </div>
        <div class='field'>
            <label class='label'>Extension</label>
            <div class='control' id='contact_extension_div'>
                <select class='input' name='department_extension' id='department_extension' disabled>
                    <option value='0' selected>Select a department first</option>
                </select>
            </div>
        </div>
        <div class='control is-fullwidth has-icons-left has-icons-right mb-2'>
            <label for='' class='label'>Contact</label>
            <div class='field has-addons'>
                <div class='control has-icons-left' style='width: 60%;'>
                <a style='height: 100%;'>
                       <input class='input' type='text' name='contact_number' id='contact_number' placeholder='Contact Number'>
                    </a>
                     
                </div>
                <div class='control' style='width: 40%;'>
                    " . Restcountries::getCountryLikeSelect() . "
                </div>
            </div>
            <i class='fa-solid fa-address-book'></i>
        </div>
        <div class='field'>
            <label class='label'>Email</label>
            <div class='control'>
                <input class='input' type='text' name='contact_email' id='contact_email' placeholder='email@exemple.pt'>
            </div>
        </div>
        </form>";
    }

    private static function getContactContriesLikeSelect()
    {
        require_once __DIR__ . '/../components/select.php';
        require_once __DIR__ . '/../API/Restcountries.php';


    }
    private static function getContactButtons()
    {
        return "<button class='button is-success' id='contact_save' onclick='createContact();'>Confirm</button>
        <button class='button is-danger' id='contact_cancel'>Cancel</button>";
    }
}