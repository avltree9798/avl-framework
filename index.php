<?php

error_reporting(E_ALL);
ini_set("log_errors", 1);
date_default_timezone_set('Asia/Jakarta');
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
        'cache'      => 'storage/caches/',
        'cache_view' => 'storage/caches/views/',
        'log_file'   => 'storage/logs/avl-' . date('Y-m-d') . '.log',
        'session'    => 'storage/sessions/'
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
ini_set('session.save_path', $GLOBALS['config']['path']['session']);
ini_set("error_log", $GLOBALS['config']['path']['log_file']);
$GLOBALS['instances'] = [];
require_once $GLOBALS['config']['path']['core'] . 'autoload.php';