<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../api/core.php';

//include database
include_once $ROOT_PATH . '/app/Stream/config/database.php';
//include object file
include_once $ROOT_PATH . '/app/Stream/media.php';

require_once 'vendor/autoload.php';

//$database = new Database();
//
//$db = $database->getConnection();
//
//$media = new Media($db);
//$result = $media->audioFiles_PER_VID(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
//$num = $result->rowCount();
//$ITEM = array();
//if ($num > 0) {
//
//    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//        extract($row);
//        $ITEM[$ID] = $row;
//    }
//}
//foreach ($ITEM as $KEY => $CHILD) {
//    generateLanguage($CHILD['ID'], $CHILD['languageID'], $CHILD['Content'], $CHILD['videoID'], $CHILD['SortID']);
//}
// Imports the Cloud Client Library
use Google\Cloud\Translate\TranslateClient;

$cred_path = "NTS Training-81d0f85327c8.json";


if (file_exists($cred_path))
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $cred_path);
else {
    echo 'Google credential expired or does not exist';
}

$translate = new TranslateClient();

// Translate text from english to french.
$result = $translate->translate('My name is kennan', [
    'target' => 'nl'
        ]);

echo $result['text'] . "\n";

// Detect the language of a string.
//$result = $translate->detectLanguage('Greetings from Michigan!');
//
//echo $result['languageCode'] . "\n";

// Get the languages supported for translation specifically for your target language.
//$languages = $translate->localizedLanguages([
//    'target' => 'en'
//        ]);
//
//foreach ($languages as $language) {
//    echo $language['name'] . "\n";
//    echo $language['code'] . "\n";
//}
//
//// Get all languages supported for translation.
//$languages = $translate->languages();
//
//foreach ($languages as $language) {
//    echo $language . "\n";
//}