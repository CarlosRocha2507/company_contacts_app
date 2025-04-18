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
}