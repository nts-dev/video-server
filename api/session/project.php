<?php

use session\project\ProjectService;
use session\RequestType;

$action = $_GET['action'];
//$mode = ;
include('Commons.php');


$bootstrap = App::getInstance();

$service = new ProjectService();

switch ($action) {

    case RequestType::PROJECT_ALL:
        $result = $service->findAll();
        $projectResource = null;
        if (isset($_GET['mode']) && $_GET['mode'] === 'json')
            $projectResource = new ProjectJSON($result);
        else
            $projectResource = new ProjectXML($result);
        $projectResource->out();
        break;

    case RequestType::PROJECT_COMBO:

        $resultArray = $service->findAll();
        XML::projectCombo($resultArray);
        break;
}