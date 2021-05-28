<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: POST");
//header("Access-Control-Max-Age: 3600");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);



define('ROOTPATH', dirname(__FILE__));
define("LOCAL_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("SERVER_NAME", $_SERVER["SERVER_NAME"]);

$ROOT_PATH = "";


$mode = $_GET['mode'];

$client =  $_GET['client'];

if (SERVER_NAME === '192.168.1.2')
    $ROOT_PATH = LOCAL_PATH_ROOT . '/Training';
else
    $ROOT_PATH = LOCAL_PATH_ROOT;





$dbpath ="";
$user = "";

switch ($mode){
    case "main":
        $dbpath = $ROOT_PATH . '/app/Stream/config/database.php';
        $user = 20196;
        break;
    
    case "demo":
        $dbpath = $ROOT_PATH . '/app/Stream/config/database_demo.php';
        break;
}

include_once $dbpath;

//include object file
include_once $ROOT_PATH . '/app/Stream/media.php';

function generateProjectId($itemId) {
    if (strlen($itemId) == 1) {
        $projectId = "P00000" . $itemId . "";
    } else if (strlen($itemId) == 2) {
        $projectId = "P0000" . $itemId . "";
    } else if (strlen($itemId) == 3) {
        $projectId = "P000" . $itemId . "";
    } else if (strlen($itemId) == 4) {
        $projectId = "P00" . $itemId . "";
    } else if (strlen($itemId) == 5) {
        $projectId = "P0" . $itemId . "";
    } else {
        $projectId = $itemId;
    }

    return $projectId;
}
