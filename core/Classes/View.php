<?php

class View
{
    /**
     * @param string $viewFile
     * @param array  $viewVars
     */
    public static function load($viewFile, $viewVars = [])
    {
        srand(time());
        $data = [
            'key' => bin2hex(random_bytes(32)),
            'iv'  => rand(0, 10101010)
        ];
        Session::set('csrf', base64_encode(json_encode($data)));
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