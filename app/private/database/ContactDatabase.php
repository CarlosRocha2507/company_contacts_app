<?php

class ContactDatabase
{
    private $servername;
    private $dbname;
    private $username;
    private $password;
    /**
     * Constructor for the ContactDatabase class.
     *
     * Initializes a new instance of the ContactDatabase class with the provided
     * database connection parameters.
     *
     * @param string $servername The hostname or IP address of the database server.
     * @param string $dbname     The name of the database to connect to.
     * @param string $username   The username for authenticating with the database.
     * @param string $password   The password for authenticating with the database.
     */
    public function __construct($servername, $dbname, $username, $password)
    {
        $this->servername = $servername;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }
    /**
     * Establishes a connection to the MySQL database.
     *
     * This method is responsible for creating and returning a connection
     * to the MySQL database using the configured credentials and settings.
     *
     * @return PDO Returns a PDO connection object on success.
     * @throws \PDOException Throws an exception if the connection fails.
     */
    public function mysqlConnection()
    {
        try {
            $conn = new PDO("mysql:host={$this->servername};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("A conexão á base de dados falhou.Tente novamente mais tarde.");
        }
    }
    /**
     * Logs in a user by verifying the provided username and password.
     *
     * @param string $user_name The username of the user attempting to log in.
     * @param string $user_password The password of the user attempting to log in.
     * @return mixed Returns user data on successful login or false on failure.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function login($user_name, $user_password)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT * FROM App_Users WHERE 1=1 AND is_active = 1 AND user_name = :user_name LIMIT 1;");
            $stmt->bindParam(':user_name', $user_name);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($user_password, $result['user_password'])) {
                    return $result;
                }
            }
            return null;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Creates a new application user in the database.
     *
     * @param string $person_name The full name of the person.
     * @param string $user_name The username for the application user.
     * @param string $user_passsword The password for the application user.
     * @return bool Returns true on success, false on failure.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function createAppUser($person_name, $user_name, $user_passsword)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("INSERT INTO App_Users (person_name, user_name, user_password) VALUES (:person_name, :user_name, :user_password)");
            $stmt->bindParam(':person_name', $person_name);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':user_password', $user_passsword);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Retrieves a list of all departments from the database.
     *
     * @return array|null Returns an array of department data or null if no departments are found.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function getDepartments()
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT 
                d.department_id,
                d.department,
                d.department_code,
                GROUP_CONCAT(dx.extension ORDER BY dx.extension SEPARATOR ', ') AS extensions
                FROM 
                App_Departments d
                LEFT JOIN 
                App_Departments_Extensions dx ON d.department_id = dx.department_id
                WHERE 
                d.is_active = 1
                GROUP BY 
                d.department_id, d.department;");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return null;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Retrieves the extensions associated with a specific department.
     *
     * @param int $department_id The ID of the department whose extensions are to be retrieved.
     * @return array|null Returns an array of extensions or null if no extensions are found.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function getDepartmentExtencions($department_id)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT extension FROM App_Departments_Extensions WHERE department_id = :department_id;");
            $stmt->bindParam(':department_id', $department_id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return null;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Retrieves the department code based on the provided department ID.
     *
     * @param int $department_id The unique identifier of the department.
     * @return string|null The code of the department if found, or null if not found.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function getDepartmentCodeById($department_id){
        try{
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT department_code FROM App_Departments WHERE department_id = :department_id;");
            $stmt->bindParam(':department_id', $department_id);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return $stmt->fetch(PDO::FETCH_ASSOC)["department_code"];
            }
            return null;
        }catch(PDOException $e){
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Creates a new department in the database.
     *
     * @param string $department_name The name of the department to be created.
     * @param string $department_code The unique code associated with the department.
     * @return int|bool Returns the ID of the newly created department on success, or false on failure.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function createDepartment($department_name, $department_code)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("INSERT INTO App_Departments (department, department_code) VALUES (:department_name , :department_code)");
            $stmt->bindParam(':department_name', $department_name);
            $stmt->bindParam(':department_code', $department_code);
            if ($stmt->execute()) {
                return $conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Creates a new department extension in the database.
     *
     * @param int $department_id The ID of the department to associate the extension with.
     * @param string $department_extension The extension to be added to the department.
     * @return bool Returns true on success, false on failure.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function createDepartmentExtension($department_id, $department_extension)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("INSERT INTO App_Departments_Extensions (department_id, extension) VALUES (:department_id, :extension)");
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':extension', $department_extension);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Updates the details of a department in the database.
     *
     * @param int $department_id The unique identifier of the department to update.
     * @param string $department_name The new name of the department.
     * @param string $department_code The new code of the department.
     * @return bool Returns true if the update was successful, false otherwise.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function updateDepartment($department_id, $department_name, $department_code)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("UPDATE App_Departments SET department = :department_name, department_code = :department_code WHERE department_id = :department_id");
            $stmt->bindParam(':department_name', $department_name);
            $stmt->bindParam(':department_code', $department_code);
            $stmt->bindParam(':department_id', $department_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Updates the extensions for a specific department in the database.
     *
     * @param int $department_id The unique identifier of the department to update.
     * @param array $department_extension The new extension to assign to the department.
     * @return bool Returns true if the update was successful, false otherwise.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function updateDepartmentExtencions($department_id, $department_extension)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("DELETE FROM App_Departments_Extensions WHERE department_id = :department_id");
            $stmt->bindParam(':department_id', $department_id);
            if ($stmt->execute()) {
                foreach ($department_extension as $extension) {
                    if (!$this->createDepartmentExtension($department_id, $extension)) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Deletes a department from the database based on the provided department ID.
     *
     * @param int $department_id The unique identifier of the department to be deleted.
     * @return bool Returns true if the department was successfully deleted, false otherwise.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function deleteDepartment($department_id)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("UPDATE App_Departments SET is_active = 0 WHERE department_id = :department_id");
            $stmt->bindParam(':department_id', $department_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Retrieves a list of contacts from the database.
     *
     * @return array|null Returns an array of contact data or null if no contacts are found.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function getContacts(){
        try{
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT c.contact_id,
                c.contry_number,
                c.contact, 
                c.email, 
                d.department,
                d.department_id,
                c.department_only,
                c.extension
                FROM App_Contacts c
                    INNER JOIN App_Departments d ON c.department_id = d.department_id
                WHERE 1=1 AND c.is_active = 1
                ORDER BY d.department ASC;");
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return null;
        }catch(PDOException $e){
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Retrieves a list of contacts that are accessible to guests.
     *
     * This method fetches and returns contact information intended for guest users.
     * It ensures that only publicly available or non-sensitive contact details are included.
     *
     * @return array|null Returns an array of contact data for guests or null if no contacts are found.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function getContactsForGests(){
        try{
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT c.contact_id,
                c.contry_number,
                c.contact, 
                c.email, 
                d.department,
                d.department_id,
                c.department_only,
                c.extension
                FROM App_Contacts c
                    INNER JOIN App_Departments d ON c.department_id = d.department_id
                WHERE 1=1 AND c.is_active = 1 AND c.department_only = 0
                ORDER BY d.department ASC;");
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return null;
        }catch(PDOException $e){
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Retrieves a list of contacts associated with a specific department.
     *
     * @param int $department_id The ID of the department whose contacts are to be retrieved.
     * @return array|null Returns an array of contact data for the specified department or null if no contacts are found.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function getContactFromDepartment($department_id){
        try{
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT c.contact_id,
                c.contry_number,
                c.contact, 
                c.email, 
                d.department,
                d.department_id,
                c.department_only,
                c.extension 
                FROM App_Contacts c
                    INNER JOIN App_Departments d ON c.department_id = d.department_id
                WHERE c.is_active = 1 AND c.department_id = :department_id
                ORDER BY d.department ASC;");
            $stmt->bindParam(':department_id', $department_id);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return null;
        }catch(PDOException $e){
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Creates a new contact in the database.
     *
     * @param string $contry_number The country code or number associated with the contact.
     * @param string $contact The name or identifier of the contact.
     * @param string $email The email address of the contact.
     * @param int $department_id The ID of the department associated with the contact.
     * @param string|null $extension The phone extension for the contact (optional).
     * @param bool $department_only Indicates if the contact is department-specific (true) or not (false).
     *
     * @return bool Returns true on success, false on failure.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function createContact($contry_number, $contact, $email, $department_id, $extension, $department_only)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("INSERT INTO App_Contacts (contry_number, contact, email, department_id, extension, department_only) VALUES (:contry_number, :contact, :email, :department_id, :extension, :department_only)");
            $stmt->bindParam(':contry_number', $contry_number);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':extension', $extension);
            $stmt->bindParam(':department_only', $department_only, PDO::PARAM_BOOL);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    /**
     * Updates the contact information in the database.
     *
     * @param int $contact_id The unique identifier of the contact to be updated.
     * @param string $contry_number The country code associated with the contact.
     * @param int $contact The contact's phone number or other contact information.
     * @param string $email The email address of the contact.
     * @param int|null $extension The phone extension for the contact (optional).
     * @param int $department_id The unique identifier of the department associated with the contact.
     * @param bool $department_only Whether to update only the department information (true) or all fields (false).
     * 
     * @return bool Returns true if the update was successful, false otherwise.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function updateContact($contact_id, $contry_number, $contact, $email, $extension, $department_id, $department_only)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("UPDATE App_Contacts SET contry_number = :contry_number, contact = :contact, email = :email, extension = :extension, department_id = :department_id, department_only = :department_only WHERE contact_id = :contact_id");
            $stmt->bindParam(':contry_number', $contry_number);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':extension', $extension);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':contact_id', $contact_id);
            $stmt->bindParam(':department_only', $department_only, PDO::PARAM_BOOL);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }   
    /**
     * Deletes a contact from the database.
     *
     * @param int $contact_id The unique identifier of the contact to be deleted.
     * 
     * @return bool Returns true if the contact was successfully deleted, false otherwise.
     * @throws \PDOException Throws an exception if the database operation fails.
     */
    public function deleteContact($contact_id)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("UPDATE App_Contacts SET is_active = 0 WHERE contact_id = :contact_id");
            $stmt->bindParam(':contact_id', $contact_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
}