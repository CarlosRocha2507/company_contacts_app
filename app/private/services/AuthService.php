<?php

class AuthService
{
    public static function login($user_name, $user_password)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        include_once __DIR__ . '/../helpers/HSession.php';
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        $data = $db->login($user_name, $user_password);
        if ($data == null)
            return false;
        HSession::startSession();
        HSession::setSession('id', $data['user_id']);
        HSession::setSession('person_name', $data['person_name']);
        return true;
    }
    public static function createAppUser($person_name, $user_name, $user_password, $secret_code)
    {
        include_once __DIR__ . '/../database/ContactDatabase.php';
        include __DIR__ . '/../config/config.php';
        $encrypted_password = self::encryptPassword($user_password);
        if ($config['secretkey'] != $secret_code)
            return false;
        $db = new ContactDatabase($config["Database"]["Localhost"]['servername'], $config["Database"]["Localhost"]['dbname'], $config["Database"]["Localhost"]['username'], $config["Database"]["Localhost"]['password']);
        return $db->createAppUser($person_name, $user_name, $encrypted_password);
    }
    public static function logout()
    {
        include_once __DIR__ . '/../helpers/HSession.php';
        HSession::startSession();
        HSession::destroySession();
    }
    private static function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}