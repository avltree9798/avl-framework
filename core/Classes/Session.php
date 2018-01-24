<?php

class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if ( ! isset($_SESSION)) {
            session_start();
        }
        foreach ($_COOKIE as $key => $value) {
            if ( ! isset($_SESSION[$key])) {
                json_decode($value);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $_SESSION[$key] = json_decode($value);
                } else {
                    $_SESSION[$key] = $value;
                }
            }
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function check($key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                if ( ! Session::check($k)) {
                    return false;
                }
            }

            return true;
        } else {
            $key = Session::generateSessionKey($key);

            return isset($_SESSION[$key]);
        }
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public static function get($key)
    {
        if (isset($_SESSION[Session::generateSessionKey($key)])) {
            return $_SESSION[Session::generateSessionKey($key)];
        } else {
            return false;
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl
     */
    public static function set($key, $value, $ttl = 0)
    {
        $_SESSION[Session::generateSessionKey($key)] = $value;
        if ($ttl === 0) {
            if (is_object($value) || is_array($value)) {
                $value = json_encode($value);
            }
            setcookie(Session::generateSessionKey($key), $value, (time() + $ttl), '/', $_SERVER['HTTP_HOST']);
        }
    }

    public static function endSession()
    {
        foreach ($_SESSION as $k => $v) {
            unset($_SESSION[$k]);
        }
        foreach ($_COOKIE as $k => $v) {
            setcookie($k, '', (time() - 5000000), '/', $_SERVER['HTTP_HOST']);
        }
        session_destroy();
    }

    /**
     * @param string $key
     */
    public static function kill($key)
    {
        if (isset($_SESSION[Session::generateSessionKey($key)])) {
            unset($_SESSION[Session::generateSessionKey($key)]);
        }
        if (isset($_COOKIE[Session::generateSessionKey($key)])) {
            setcookie(Session::generateSessionKey($key), '', (time() - 5000000), '/' . $_SERVER['HTTP_HOST']);
        }
    }

    /**
     * @param string key
     * @return string
     */
    public static function generateSessionKey($key)
    {
        $prepend = __DIR__;
        $append = $GLOBALS['config']['appName'] . $GLOBALS['config']['version'];

        return md5($prepend . $key . $append);
    }

}
