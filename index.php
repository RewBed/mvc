<?php

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// require 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    include_once(dirname(__FILE__) . '/' . $class . '.php');
});

\Core\MVC::init();