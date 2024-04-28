<?php

namespace Jauhar\System\Session;

class SessionHelper
{
    public static function set($key = null, $value = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Set flashdata
        $_SESSION[$key] = $value;
    }
}
