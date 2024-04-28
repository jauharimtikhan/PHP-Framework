<?php

namespace Jauhar\System;

class Input
{
    public static function post($key = null)
    {
        if ($key !== null) {
            return isset($_POST[$key]) ? $_POST[$key] : null;
        }
        return $_POST;
    }

    public static function get($key = null)
    {
        if ($key !== null) {
            return isset($_GET[$key]) ? $_GET[$key] : null;
        }
        return $_GET;
    }

    public static function session($key = null)
    {
        if ($key !== null) {
            return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        }
        return $_SESSION;
    }
}
