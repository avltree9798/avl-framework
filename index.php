<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
$GLOBALS['config'] = [
    'appName'  => 'AVL Framework',
    'domain'   => 'http://localhost:8080',
    'version'  => '0.0.1',
    'path'     => [
        'app'   => 'app/',
        'core'  => 'core/',
        'index' => 'index.php',
        'view'  => 'resources/views/'
    ],
    'defaults' => [
        'controller' => 'Main',
        'method'     => 'index'
    ],
    'routes'   => [

    ],
    'database' => [
        'hostname' => 'localhost',
        'username' => 'dev',
        'password' => 'leonie',
        'database' => 'pos'
    ]
];
$GLOBALS['instances'] = [];
require_once $GLOBALS['config']['path']['core'] . 'autoload.php';