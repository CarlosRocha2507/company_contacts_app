<?php

class AuthService
{
    /**
     * Logs in a user by validating their username and password.
     *
     * @param string $user_name The username of the user attempting to log in.
     * @param string $user_password The password of the user attempting to log in.
     * @return bool Return true if login is successful, false otherwise.
     */
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
    /**
     * Creates a new application user.
     *
     * @param string $person_name The full name of the person to associate with the user account.
     * @param string $user_name The username for the new user account.
     * @param string $user_password The password for the new user account.
     * @param string $secret_code A secret code used for additional verification or security purposes.
     * @return bool Returns true if the user was created successfully, false otherwise.
     */
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
    /**
     * Logs out the currently authenticated user.
     *
     * This method handles the process of logging out a user by clearing
     * any session or authentication-related data. It does not return any value.
     *
     * @return void
     */
    public static function logout()
    {
        include_once __DIR__ . '/../helpers/HSession.php';
        HSession::startSession();
        HSession::destroySession();
    }
    /**
     * Encrypts the given password using a secure hashing algorithm.
     *
     * @param string $password The plain text password to be encrypted.
     * @return string The encrypted password hash.
     */
    private static function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}