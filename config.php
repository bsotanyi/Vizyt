<?php

session_start();
date_default_timezone_set('Europe/Belgrade');

// ============================================================================ //

require_once('env.php');

spl_autoload_register(function($class_name) {
    $file_path = stream_resolve_include_path('class/' . $class_name . '.php');
    if ($file_path !== false) {
        include $file_path;
    }
});

$_CONFIG = [
    'debug' => true,
    'app_name' => 'Adminers',
];

define('NOW', date('Y-m-d H:i:s'));
define('TODAY', date('Y-m-d'));

if ($_CONFIG['debug']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); 
}

DB::connect(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD, DB_CHARSET);

// ============================================================================ //