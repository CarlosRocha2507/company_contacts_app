<?php

class HSession
{
    /**
     * Starts a new session or resumes the current session.
     * This method ensures that a session is properly initiated
     * and can be used to store and retrieve session data.
     *
     * @return void
     */
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    /**
     * Sets a session variable with the specified key and value.
     *
     * @param string $key The key to identify the session variable.
     * @param mixed $value The value to be stored in the session variable.
     * @return void
     */
    public static function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    /**
     * Retrieves the value associated with a specific key from the session.
     *
     * @param string $key The key used to retrieve the session value.
     * @return mixed The value associated with the given key, or null if the key does not exist.
     */
    public static function getSession($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }
    /**
     * Destroys the current session.
     *
     * This method is responsible for terminating the active session by 
     * unsetting all session variables and destroying the session data.
     * It ensures that no residual session information remains.
     *
     * @return void
     */
    public static function destroySession()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
    /**
     * Checks if the user is currently logged in.
     *
     * This method determines whether a user session is active, indicating
     * that the user is logged into the application.
     *
     * @return bool Returns true if the user is logged in, false otherwise.
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['id']);
    }
    /**
     * Checks if the current page is not the login page.
     *
     * This method is used to determine whether the user is on a page
     * other than the login page. It can be useful for enforcing access
     * control or redirecting users to the appropriate pages based on
     * their authentication status.
     *
     * @return bool Returns true if the current page is not the login page, false otherwise.
     */
    public static function isNotLoginPage()
    {
        return $_SERVER['REQUEST_URI'] != '/login' && $_SERVER['REQUEST_URI'] != '/register' && $_SERVER['REQUEST_URI'] != '/' && $_SERVER['REQUEST_URI'] != '/contacts';
    }
}