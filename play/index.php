<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header("Access-Control-Allow-Origin: *");


include('../includes.php');
include('../api/session/Commons.php');


$ROOT = WEBURL . \Boot::WWWROOT . "/nts-video-api/";
$id = $_GET['id'];


$bootstrap = App::getInstance();


$session = $bootstrap::startSessionIfNotAvailable(
    20196, '1kenan');

$mediaService = new MediaService();

$resultArray = $mediaService->findByHashing(trim($id));


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Player</title>
    <?php
    CSSPackage::BOOTSTRAP();
    ?>

    <style>
        html, body {
            height: 100%
        }

        .video {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>


<div class="container-fluid h-100">
    <div class="row justify-content-center h-100">
        <video
                id="my-video"
                class="video-js video"
                controls
                preload="auto"
                data-setup="{}">
            <source src="<?php echo $resultArray->videoLink_raw; ?>" type="video/mp4">

        </video>
    </div>
</div>

</body>
</html>