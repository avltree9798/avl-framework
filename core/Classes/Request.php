<?php

class Request
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

    /**
     * @param string $key
     * @return bool|string
     */
    public static function post($key)
    {
        return (isset($_POST[$key])) ? $_POST[$key] : false;
    }

    /**
     * @param string $key
     * @return bool|string
     */
    public static function get($key)
    {
        return (isset($_GET[$key])) ? urldecode($_GET[$key]) : false;
    }

    /**
     * @param string $key
     * @return bool|string
     */
    public static function allMethod($key)
    {
        if (Request::get($key)) {
            return Request::get($key);
        } else {
            if (Request::post($key)) {
                return Request::post($key);
            } else {
                return false;
            }
        }
    }

    /**
     * @return array
     */
    public static function all()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $server = $requestMethod === 'GET' ? $_GET : $_POST;
        $return = [];
        foreach ($server as $key => $value) {
            $return[$key] = $value;
        }

        return $return;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function only($array = [])
    {
        $data = Request::all();
        $return = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $array)) {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /**
     * @param string $url
     * @param array  $params
     * @return string
     */
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

}