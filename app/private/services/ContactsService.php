<?php

class ContactsService
{
    /**
     * Retrieves a list of contacts.
     *
     * This method fetches and returns all the contacts available in the system.
     * It is a static method and can be called without instantiating the class.
     *
     * @return string An HTML component representing the list of contacts.
     */
    public static function getContacts()
    {
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/table.php';
        require_once __DIR__ . '/../services/DepartmentService.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $contacts = $db->getContacts();
        $filters = "<div class='field''>
            <label class='label'>Department</label>
            <div class='control has-icons-left'>
               " . DepartmentService::getDepartmentsLikeSelect('department_id_filter') . "
            </div>
        </div>";
        $header_button = '<button class="card-header-icon js-modal-trigger" data-target="contacts_modal" aria-label="more options" onclick="createModal();">
        <span class="icon"><i class="fa-solid fa-plus"></i></span>
        </button>';
        return generateTaleWithFilter("Contacts list", $filters, $header_button, self::getHeaders(), self::getRows($contacts, false));
    }
    /**
     * Retrieves a list of contacts available for guests based on the specified department ID.
     *
     * @param int $department_id The ID of the department for which to fetch contacts.
     * @return string An HTML component representing the list of contacts for not login users.
     */
    public static function getContactsForGests($department_id)
    {
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/table.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        if ($department_id != null) {
            $contacts = $db->getContactFromDepartment($department_id);
        } else {
            $contacts = $db->getContactsForGests();
        }
        $header_button = '<button class="card-header-icon" onclick="exportToExcel(\'search-table\', \'contacts\');" aria-label="more options">
        <span class="icon"><i class="fa-solid fa-file-arrow-down"></i></span>
        </button>';
        return generateTaleWithFilter("Contacts list", null, $header_button, self::getHeaders(), self::getRows($contacts, true));
    }
    /**
     * Retrieves a contact associated with a specific department.
     *
     * This method fetches and returns an HTML component representing the contact
     * information associated with the specified department ID.
     *
     * @param int $department_id The ID of the department to retrieve the contact from.
     * @return string An HTML component representing the contact information,
     */
    public static function getContactFromDepartment($department_id)
    {
        require_once __DIR__ . '/../database/ContactDatabase.php';
        require __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../components/table.php';
        require_once __DIR__ . '/../services/DepartmentService.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $contacts = $db->getContactFromDepartment($department_id);
        $filters = "<div class='field''>
            <label class='label'>Department</label>
            <div class='control has-icons-left'>
               " . DepartmentService::getDepartmentsLikeSelect('department_id_filter') . "
            </div>
        </div>";
        $header_button = '<button class="card-header-icon js-modal-trigger" data-target="contacts_modal" aria-label="more options" onclick="createModal();">
        <span class="icon"><i class="fa-solid fa-plus"></i></span>
        </button>';
        return generateTaleWithFilter("Contacts list", $filters, $header_button, self::getHeaders(), self::getRows($contacts, false));
    }
    /**
     * Retrieves the contact modal.
     *
     * This method is responsible for fetching and returning the contact modal
     * used in the application. The modal may include details such as contact
     * information, layout, or other relevant data required for display.
     *
     * @return mixed The contact modal data or structure.
     */
    public static function getContactModal()
    {
        require_once __DIR__ . '/../components/modal.php';
        return generateModal("contacts_modal", "Contact", self::getContactForm(), self::getContactButtons());
    }
    /**
     * Creates a new contact with the provided details.
     *
     * @param string $contry_number The country code or number associated with the contact.
     * @param string $contact The name or identifier of the contact.
     * @param string $email The email address of the contact.
     * @param int $department_id The ID of the department associated with the contact.
     * @param string|null $extension The phone extension for the contact (optional).
     * @param bool $department_only Indicates if the contact is department-specific only.
     * 
     * @return bool Returns true on success or false on failure.
     */
    public static function createContact($contry_number, $contact, $email, $department_id, $extension, $department_only)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->createContact($contry_number, $contact, $email, $department_id, $extension, $department_only);
    }
    /**
     * Updates the contact information in the system.
     *
     * @param int $contact_id The unique identifier of the contact to be updated.
     * @param string $contact_number The phone number of the contact.
     * @param string $contact The name of the contact.
     * @param string $email The email address of the contact.
     * @param int $extension The phone extension for the contact.
     * @param int $department_id The unique identifier of the department associated with the contact.
     * @param bool $department_only Indicates whether the update is restricted to the department information only.
     *
     * @return bool Returns true on success or false on failure.
     */
    public static function updateContact($contact_id, $contact_number, $contact, $email, $extension, $department_id, $department_only)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->updateContact($contact_id, $contact_number, $contact, $email, $extension, $department_id, $department_only);
    }
    /**
     * Soft deletes a contact by its ID.
     *
     * This method performs a soft delete on the contact with the given ID,
     * meaning the contact is marked as deleted without being permanently removed
     * from the database.
     *
     * @param int $contact_id The ID of the contact to be deleted.
     * @return bool Returns true if the contact was successfully soft deleted, 
     *              or false if the operation failed.
     */
    public static function deleteContact($contact_id)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->deleteContact($contact_id);
    }
    /**
     * Generates and returns the headers for the contacts table.
     *
     * This helper method is used to define the column headers for the contacts table.
     * It ensures consistency and reusability across the application when rendering
     * or processing contact-related data.
     *
     * @return string An string representing the headers for the contacts table.
     */
    private static function getHeaders()
    {
        return "<th>Department</th>
        <th>Contact</th>
        <th>Extension</th>
        <th>Email</th>
        <th>Department only</th>
        <th>Actions</th>";
    }
    /**
     * Retrieves rows of contact data based on the provided input.
     *
     * @param array $data The input data used to filter or retrieve the rows.
     * @param bool $is_for_gest Indicates whether the data is being retrieved for a specific purpose (e.g., for a management interface).
     * @return string The rows of contact data retrieved based on the input parameters.
     */
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
                $rows .= "<td>" . ($row['department_only'] == 1 ? 'Yes' : 'No') . "</td>";
                $rows .= "<td>
                    <a href='https://wa.me/{$row['contry_number']}{$row['contact']}' target='_blank'><button class='button is-small is-success'><span class='icon'><i class='fa-brands fa-whatsapp'></i></span></button></a>
                    <a href='mailto:{$row['email']}' target='_blank'><button class='button is-small is-link');'><span class='icon'><i class='fa-solid fa-paper-plane'></i></span></button></a>
                </td>";
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
            $rows .= "<td>" . ($row['department_only'] == 1 ? 'Yes' : 'No') . "</td>";
            $rows .= "<td>
            <a href='https://wa.me/{$row['contry_number']}{$row['contact']}' target='_blank'><button class='button is-small is-success'><span class='icon'><i class='fa-brands fa-whatsapp'></i></span></button></a>
            <a href='mailto:{$row['email']}' target='_blank'><button class='button is-small is-link');'><span class='icon'><i class='fa-solid fa-paper-plane'></i></span></button></a>
            <button class='button is-small is-info js-modal-trigger' data-target='contacts_modal' aria-label='more options' onclick='updateModal({$row["department_id"]}, {$row["contact_id"]}, {$row["contact"]}, \"" . $row["contry_number"] . "\",\"" . $row["email"] . "\", \"" . $row["extension"] . "\", {$row["department_only"]});'><span class='icon'><i class='fa-solid fa-pen-to-square'></i></span></button>
            <button class='button is-small is-danger'><span class='icon is-small' onclick='deleteContact({$row["contact_id"]})'><i class='fa-solid fa-trash'></i></span></button>
            </td>";
            $rows .= "</tr>";
        }
        return $rows;
    }
    /**
     * Retrieves the contact form data.
     *
     * This method is responsible for fetching and returning the data
     * associated with the contact form. It is a private static method
     * and is intended to be used internally within the class.
     *
     * @return mixed The contact form data.
     */
    private static function getContactForm()
    {
        include_once __DIR__ . '/../services/DepartmentService.php';
        include_once __DIR__ . '/../API/Restcountries.php';
        return "<form id='contact_form' method='POST' action=''>
        <div class='field''>
            <label class='label'>Department</label>
            <div class='control has-icons-left'>
               " . DepartmentService::getDepartmentsLikeSelect('department_id') . "
            </div>
        </div>
        <div class='field'>
            <label class='label'>Extension</label>
            <div class='control has-icons-left' id='contact_extension_div'>
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
                    <span class='icon is-small is-left'>
                        <i class='fa-solid fa-address-book'></i>
                    </span>
                </div>
                <div class='control' style='width: 40%;'>
                    " . Restcountries::getCountryLikeSelect() . "
                </div>
            </div>
        </div>
        <div class='field'>
            <label class='label'>Email</label>
            <div class='control has-icons-left'>
                <input class='input' type='text' name='contact_email' id='contact_email' placeholder='email@exemple.pt'>
                <span class='icon is-small is-left'>
                    <i class='fa-solid fa-envelope'></i>
                </span>
            </div>
        </div>
        <div class='field'>
            <div class='control'>
                <label class='checkbox'>
                <input type='checkbox' id='department_only' name='department_only'>
                    Department access only
                </label>
            </div>
        </div>
        </form>";
    }
    /**
     * Returns the buttons used to manage contacts.
     *
     * This method generates and provides the necessary buttons
     * for performing actions related to contact management.
     *
     * @return string A string containing the HTML buttons for managing contacts.
     */
    private static function getContactButtons()
    {
        return "<button class='button is-success' id='contact_save' onclick='createContact();'>Confirm</button>
        <button class='button is-danger' id='contact_cancel'>Cancel</button>";
    }
}