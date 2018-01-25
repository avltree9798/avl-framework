<?php
    spl_autoload_register(function($class){
        $cacheFile = $GLOBALS["config"]["path"]["cache"]."classloc.cache";
        if($GLOBALS["config"]["cache_enabled"] && file_exists($cacheFile)){
            $locations = unserialize(file_get_contents($cacheFile));
        }else{
            $locations = array();
        }
        if(isset($locations[$class])) {
            $instantiable = $locations[$class]["instantiable"];
            $classFile = $locations[$class]["classFile"];
        }else{
            $core_path = $GLOBALS['config']['path']['core'];
            $app_path = $GLOBALS['config']['path']['app'];
            $instantiable = true;
            if(file_exists("{$core_path}Abstracts/{$class}.php")){
                $instantiable = false;
                $classFile =  "{$core_path}Abstracts/{$class}.php";
            }else if(file_exists("{$core_path}Classes/{$class}.php")){
                $classFile =  "{$core_path}Classes/{$class}.php";
            }else if(file_exists("{$core_path}Interfaces/{$class}.php")){
                $instantiable = false;
                $classFile =  "{$core_path}Interfaces/{$class}.php";
            }else if(file_exists("{$app_path}Abstracts/{$class}.php")){
                $instantiable = false;
                $classFile =  "{$app_path}Abstracts/{$class}.php";
            }else if(file_exists("{$app_path}Controllers/{$class}.php")){
                $classFile =  "{$app_path}Controllers/{$class}.php";
            }else if(file_exists("{$app_path}Interfaces/{$class}.php")){
                $instantiable = false;
                $classFile =  "{$app_path}Interfaces/{$class}.php";
            }else if(file_exists("{$app_path}Libs/{$class}.php")){
                $classFile =  "{$app_path}Libs/{$class}.php";
            }else if(file_exists("{$app_path}Models/{$class}.php")){
                $classFile =  "{$app_path}Models/{$class}.php";
            }else if(file_exists("{$core_path}Classes/Exception/{$class}.php")) {
                $instantiable = false;
                $classFile =  "{$core_path}Classes/Exception/{$class}.php";
            }else if(file_exists("{$core_path}Classes/Middleware/{$class}.php")) {
                $instantiable = false;
                $classFile =  "{$core_path}Classes/Middleware/{$class}.php";
            }else if(file_exists("{$app_path}Middleware/{$class}.php")) {
                $instantiable = false;
                $classFile =  "{$app_path}Middleware/{$class}.php";
            }else if(file_exists("{$core_path}Classes/Template/{$class}.php")){
                $classFile = "{$core_path}Classes/Template/{$class}.php";
            }
            if ($GLOBALS['config']['cache_enabled']) {
                $locations[$class] = [
                    'instantiable' => $instantiable,
                    'classFile'    => $classFile
                ];
                file_put_contents($cacheFile, serialize($locations));
            }
        }
        if(isset($classFile)){
            if(file_exists($classFile)){
                require_once $classFile;
            }
            if($instantiable){
                foreach($GLOBALS['instances'] as $instance){
                    $instance->class = new $class();
                }
            }
        }

    });

    require_once $GLOBALS['config']['path']['app'].'routes.php';
    require_once $GLOBALS['config']['path']['app'].'helpers.php';
    new Router();