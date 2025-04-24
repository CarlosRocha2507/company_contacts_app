<?php

class ContactDatabase
{
    private $servername;
    private $dbname;
    private $username;
    private $password;
    public function __construct($servername, $dbname, $username, $password)
    {
        $this->servername = $servername;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }
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
    public function getDepartments()
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT 
                d.department_id,
                d.department,
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
    public function createDepartment($department_name)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("INSERT INTO App_Departments (department) VALUES (:department_name)");
            $stmt->bindParam(':department_name', $department_name);
            if ($stmt->execute()) {
                return $conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
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
    public function updateDepartment($department_id, $department_name)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("UPDATE App_Departments SET department = :department_name WHERE department_id = :department_id");
            $stmt->bindParam(':department_name', $department_name);
            $stmt->bindParam(':department_id', $department_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
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
    public function getContacts(){
        try{
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT c.contact_id,
                c.contry_number,
                c.contact, 
                c.email, 
                d.department,
                d.department_id,
                c.extension
                FROM App_Contacts c
                    INNER JOIN App_Departments d ON c.department_id = d.department_id
                WHERE c.is_active = 1;");
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return null;
        }catch(PDOException $e){
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    public function getContactFromDepartment($department_id){
        try{
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("SELECT c.contact_id,
                c.contry_number,
                c.contact, 
                c.email, 
                d.department,
                d.department_id,
                c.extension 
                FROM App_Contacts c
                    INNER JOIN App_Departments d ON c.department_id = d.department_id
                WHERE 1=1 AND c.department_id = :department_id AND c.is_active = 1;");
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
    public function createContact($contry_number, $contact, $email, $department_id, $extension)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("INSERT INTO App_Contacts (contry_number, contact, email, department_id, extension) VALUES (:contry_number, :contact, :email, :department_id, :extension)");
            $stmt->bindParam(':contry_number', $contry_number);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':extension', $extension);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }
    public function updateContact($contact_id, $contry_number, $contact, $email, $department_id)
    {
        try {
            $conn = $this->mysqlConnection();
            $stmt = $conn->prepare("UPDATE App_Contacts SET contry_number = :contry_number, contact = :contact, email = :email, department_id = :department_id WHERE contact_id = :contact_id");
            $stmt->bindParam(':contry_number', $contry_number);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':contact_id', $contact_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            die("ERROR:" . $e->getMessage() . " ON FILE: " . $e->getFile() . " ON LINE: " . $e->getLine());
        }
    }   
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