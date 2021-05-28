<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../api/core.php';

//include database
include_once $ROOT_PATH . '/app/Stream/config/database.php';
//include object file
include_once $ROOT_PATH . '/app/Stream/media.php';

require_once 'vendor/autoload.php';


$database = new AppDatabase();

$db = $database->getConnection();

$media = new Media($db);

$result = $media->audioFiles_PER_VID(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
$num = $result->rowCount();
$ITEM = array();
if ($num > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $ITEM[$ID] = $row;
    }
}

//echo '<pre>';
//print_r($ITEM);

foreach ($ITEM as $KEY => $CHILD) {
    generateLanguage($CHILD['ID'], $CHILD['languageID'], $CHILD['Content'], $CHILD['videoID'], $CHILD['SortID']);
}

// Imports the Cloud Client Library
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use GuzzleHttp;

function generateLanguage($itemId, $lang, $my_text, $videoID, $sort) {
    global $media;
    $cred_path = "NTS Training-81d0f85327c8.json";


    if (file_exists($cred_path))
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $cred_path);
    else {
        echo 'Google credential expired or does not exist';
    }


    $filename = $sort . '.mp3';
    $pathname = 'f_' . $videoID;

    $lang_code = '';
    switch ($lang) {
        case 1:
            $lang_code = 'en-US';
            break;
        case 7: //german
            $lang_code = 'de-DE';
            break;
        case 4: //Dutch
            $lang_code = 'nl-NL';
            break;
        case 6: //French
            $lang_code = 'fr-FR';
            break;
        case 9: //Spanish
            $lang_code = 'es-ES';
            break;
    }
    $basepath = '../uploads/audio/' . $pathname;


    if (!is_dir($basepath))
        mkdir($basepath);

    $fullpath = $basepath . '/' . $lang;

    $guzzleClient = new GuzzleHttp\Client(['verify' => false]);

// instantiates a client
    $client = new TextToSpeechClient();

// sets text to be synthesised
    $synthesisInputText = (new SynthesisInput())
            ->setText($my_text);


// build the voice request, select the language code ("en-US") and the ssml
// voice gender
    $voice = (new VoiceSelectionParams())
            ->setLanguageCode($lang_code)
            ->setSsmlGender(SsmlVoiceGender::MALE);

// Effects profile
    $effectsProfileId = "telephony-class-application";

// select the type of audio file you want returned
    $audioConfig = (new AudioConfig())
            ->setAudioEncoding(AudioEncoding::MP3)
            ->setEffectsProfileId(array($effectsProfileId));

// perform text-to-speech request on the text input with selected voice
// parameters and audio file type
    $response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
    $audioContent = $response->getAudioContent();

// the response's audioContent is binary
    if (!is_dir($fullpath))
        mkdir($fullpath);

    if (file_put_contents($fullpath . "/" . $filename, $audioContent))
//        
        $update = $media->audioFileDetail_UPDATE($itemId, 'Status', 1);
    if ($update)
        echo json_encode(array("response" => true, "state" => 'success', "message" => 'Audio content written to ' . $filename));
    else
        echo json_encode(array("response" => true, "state" => 'fail', "message" => 'No audio was generated'));
}

//require_once 'vendor/autoload.php';


//$cred_path = "NTS Training-81d0f85327c8.json";
//
//if (file_exists($cred_path))
//    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $cred_path);
//else {
//    echo 'Google credential expired or does not exist';
//}
//
//$my_text = $_POST['text'];
//$filename = $_POST['filename'];
//$pathname = $_POST['path'];
//$lang = $_POST['lang'];
//
//$lang_code = '';
//switch ($lang) {
//    case 1:
//        $lang_code = 'en-US';
//        break;
//    case 7: //german
//        $lang_code = 'de-DE';
//        break;
//    case 4: //Dutch
//        $lang_code = 'nl-NL';
//        break;
//    case 6: //French
//        $lang_code = 'fr-FR';
//        break;
//    case 9: //Spanish
//        $lang_code = 'es-ES';
//        break;
//}
//
//$fullpath = '../uploads/audio/' . $pathname . '/' . $lang;
//
//// Imports the Cloud Client Library
//use Google\Cloud\TextToSpeech\V1\AudioConfig;
//use Google\Cloud\TextToSpeech\V1\AudioEncoding;
//use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
//use Google\Cloud\TextToSpeech\V1\SynthesisInput;
//use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
//use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
//use GuzzleHttp;
//
//$guzzleClient = new GuzzleHttp\Client(['verify' => false]);
//
//// instantiates a client
//$client = new TextToSpeechClient();
//
//// sets text to be synthesised
//$synthesisInputText = (new SynthesisInput())
//        ->setText($my_text);
//
//
//// build the voice request, select the language code ("en-US") and the ssml
//// voice gender
//$voice = (new VoiceSelectionParams())
//        ->setLanguageCode($lang_code)
//        ->setSsmlGender(SsmlVoiceGender::MALE);
//
//// Effects profile
//$effectsProfileId = "telephony-class-application";
//
//// select the type of audio file you want returned
//$audioConfig = (new AudioConfig())
//        ->setAudioEncoding(AudioEncoding::MP3)
//        ->setEffectsProfileId(array($effectsProfileId));
//
//// perform text-to-speech request on the text input with selected voice
//// parameters and audio file type
//$response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
//$audioContent = $response->getAudioContent();
//
//// the response's audioContent is binary
//if (!is_dir($fullpath))
//    mkdir($fullpath);
//
//if (file_put_contents($fullpath . "/" . $filename, $audioContent))
//    echo json_encode(array("response" => true, "state" => 'success', "message" => 'Audio content written to ' . $filename));
//else
//    echo json_encode(array("response" => true, "state" => 'fail', "message" => 'No audio was generated'));