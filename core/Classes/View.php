<?php

class View
{
    public static function load($viewFile, $viewVars = [])
    {
        extract($viewVars);
        $viewFileCheck = explode('.', $viewFile);
        if ( ! isset($viewFileCheck[1])) {
            $viewFile .= '.php';
        }
        $viewFile = str_replace("::", "/", $viewFile);
        require_once $GLOBALS['config']['path']['view'] . $viewFile;
    }
}