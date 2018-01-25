<?php

class View
{
    /**
     * @param string $viewFile
     * @param array  $viewVars
     */
    public static function load($viewFile, $viewVars = [])
    {
        generate_csrf_token();
        $viewFile = str_replace(".", "/", $viewFile);
        $viewFile .= '.avl.php';
        $fileName = $GLOBALS['config']['path']['view'] . $viewFile;
        if (file_exists($fileName)) {
            $compiler = new Compiler();
            $compiler->setView($fileName, $viewVars)->render();
        } else {
            echo '404 view not found';
            http_response_code(404);
        }
    }
}