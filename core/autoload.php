<?php
    spl_autoload_register(function($class){
        $core_path = $GLOBALS['config']['path']['core'];
        $app_path = $GLOBALS['config']['path']['app'];
        $instantiable = true;
        if(file_exists("{$core_path}Abstracts/{$class}.php")){
            $instantiable = false;
            require_once "{$core_path}Abstracts/{$class}.php";
        }else if(file_exists("{$core_path}Classes/{$class}.php")){
            require_once "{$core_path}Classes/{$class}.php";
        }else if(file_exists("{$core_path}Interfaces/{$class}.php")){
            $instantiable = false;
            require_once "{$core_path}Interfaces/{$class}.php";
        }else if(file_exists("{$app_path}Abstracts/{$class}.php")){
            $instantiable = false;
            require_once "{$app_path}Abstracts/{$class}.php";
        }else if(file_exists("{$app_path}Controllers/{$class}.php")){
            require_once "{$app_path}Controllers/{$class}.php";
        }else if(file_exists("{$app_path}Interfaces/{$class}.php")){
            $instantiable = false;
            require_once "{$app_path}Interfaces/{$class}.php";
        }else if(file_exists("{$app_path}Libs/{$class}.php")){
            require_once "{$app_path}Libs/{$class}.php";
        }else if(file_exists("{$app_path}Models/{$class}.php")){
            require_once "{$app_path}Models/{$class}.php";
        }
        if($instantiable){
            foreach($GLOBALS['instances'] as $instance){
                $instance->class = new $class();
            }
        }
    });
    require_once $GLOBALS['config']['path']['app'].'routes.php';
    require_once $GLOBALS['config']['path']['app'].'helpers.php';
    new Router();
?>