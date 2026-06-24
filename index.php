<?php

session_start();

spl_autoload_register(function ($class_name){
    $file = __DIR__ . "/" . implode("/", explode("_", $class_name)) . ".php";

    if (is_readable($file)) {
        require_once $file;
    }
});

$dispatcher = new Utils_Dispatcher();
$dispatcher->dispatch();
