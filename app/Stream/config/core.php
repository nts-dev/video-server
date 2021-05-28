<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);



define('ROOTPATH', dirname(__FILE__));
define("LOCAL_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("SERVER_NAME", $_SERVER["SERVER_NAME"]);

$ROOT_PATH = "";

if (SERVER_NAME === '192.168.1.2')
    $ROOT_PATH = LOCAL_PATH_ROOT . '/Training';
else
    $ROOT_PATH = LOCAL_PATH_ROOT;




