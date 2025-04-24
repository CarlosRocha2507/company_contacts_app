<?php

class HSession
{
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public static function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public static function getSession($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }
    public static function destroySession()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
    public static function isLoggedIn()
    {
        return isset($_SESSION['id']);
    }
    public static function isNotLoginPage()
    {
        return $_SERVER['REQUEST_URI'] != '/login' && $_SERVER['REQUEST_URI'] != '/register' && $_SERVER['REQUEST_URI'] != '/' && $_SERVER['REQUEST_URI'] != '/contacts';
    }
}