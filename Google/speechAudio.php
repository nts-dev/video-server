<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);





require_once 'vendor/autoload.php';

// Imports the Cloud Client Library
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\StreamingRecognitionConfig;

$cred_path = "NTS Training-81d0f85327c8.json"; //Credential
//set up api credential
if (file_exists($cred_path))
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $cred_path);
else {
    echo 'Google credential expired or does not exist';
}

$recognitionConfig = new RecognitionConfig();
$recognitionConfig->setEncoding(AudioEncoding::FLAC);
$recognitionConfig->setSampleRateHertz(44100);
$recognitionConfig->setLanguageCode('en-US');
$config = new StreamingRecognitionConfig();
$config->setConfig($recognitionConfig);

$audioResource = fopen('output.mp3', 'r');

$responses = $speechClient->recognizeAudioStream($config, $audioResource);

foreach ($responses as $element) {
    echo $element . "<br>";
}


