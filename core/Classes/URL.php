<?php

class URL
{

    /**
     * @param $number
     * @return bool
     */
    public static function part($number)
    {
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $parts = explode('/', $uri[0]);

        return isset($parts[$numbers]) ? $parts[$numbers] : false;
    }

    public static function post($key)
    {
        return (isset($_POST[$key])) ? $_POST[$key] : false;
    }

    public static function get($key)
    {
        return (isset($_GET[$get])) ? urldecode($_GET[$get]) : false;
    }

    public static function request($key)
    {
        if (URL::get($key)) {
            return URL::get($key);
        } else {
            if (URL::post($key)) {
                return URL::post($key);
            } else {
                return false;
            }
        }
    }

    public static function build($url, $params = [])
    {
        if (strpos($url, '//') === false) {
            $prefix = '//' . $GLOBALS['config']['domain'];
        } else {
            $prefix = '';
        }
        $append = '';
        foreach ($params as $name => $param) {
            $append .= ($append == '') ? '?' : '&';
            $append .= urlencode($key) . '=' . urlencode($param);
        }
        $params = implode("&", $params);

        return $prefix . $append;
    }

    public static function redirect($to, $exit = true)
    {
        if (headers_sent()) {
            echo '<script>document.location="' . $to . '"</script>';
        } else {
            header('Location: ' . $to);
        }
        if ($exit) {
            die();
        }
    }
}