<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
$GLOBALS['config'] = [
    'appName'       => 'AVL Framework',
    'domain'        => 'http://localhost:8080',
    'version'       => '0.0.2',
    'cache_enabled' => true,
    'path'          => [
        'app'        => 'app/',
        'core'       => 'core/',
        'index'      => 'index.avl.php',
        'view'       => 'resources/views/',
        'cache'      => 'caches/',
        'cache_view' => 'caches/views/'
    ],
    'defaults'      => [
        'controller' => 'Main',
        'method'     => 'index'
    ],
    'routes'        => [

    ],
    'database'      => [
        'hostname' => 'localhost',
        'username' => 'dev',
        'password' => 'leonie',
        'database' => 'pos'
    ]
];
date_default_timezone_set('Asia/Jakarta');
$GLOBALS['instances'] = [];
require_once $GLOBALS['config']['path']['core'] . 'autoload.php';