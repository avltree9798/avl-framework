<?php
/**
 * Created by PhpStorm.
 * User: avltree
 * Date: 26/01/18
 * Time: 23:27
 */

class Response
{
    /**
     * @param mixed $value
     */
    public static function json($value)
    {
        header('Content-type: application/json');
        echo json_encode($value);
    }

    /**
     * @param string $to
     * @param bool   $exit
     */
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