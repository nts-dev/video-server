<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once '../api/core.php';

//include database
include_once $ROOT_PATH . '/app/Stream/config/database.php';
//include object file
include_once $ROOT_PATH . '/app/Stream/media.php';

$database = new AppDatabase();

$db = $database->getConnection();

$media = new Media($db);



$language_grid_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

$pathname = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);

$lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_NUMBER_INT);

//request fo audio file contents from database
$result = $media->audioFiles_TIMESTAMP($language_grid_id);

$num = $result->rowCount();

$ITEM = array();

if ($num > 0) {

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $ITEM[] = $row;
    }
}

//echo '<pre>';
//print_r($ITEM);


$string = "<speak>"; //SSML string to be traslated to speech

foreach ($ITEM as $KEY => $VAL) {

    //get time diffrence
    $begin = new DateTime("2019-07-02 " . $VAL['BeginTime']);
    $end = new DateTime("2019-07-02 " . $VAL['previousEndTime']);
    $break = $begin->getTimestamp() - $end->getTimestamp();

    //put break beteween spoken statements
    //only put break from the 2nd statement
    $string .= '<break time="' . $break . 's"/>';

    //actual spoken statement
    $string .= $VAL['Content'] . '.';
}

$string .="</speak>";

//$handle = fopen($string, 'w') or die('Cannot open file:  ' . $string);
//exit;


require_once 'vendor/autoload.php';

// Imports the Cloud Client Library
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use GuzzleHttp;

$cred_path = "NTS Training-81d0f85327c8.json"; //Credential
//set up api credential
if (file_exists($cred_path))
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $cred_path);
else {
    echo 'Google credential expired or does not exist';
}


$filename = 'audiomovie.mp3';


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


//save to this path
$basepath = '../uploads/audiomovie/' . $pathname;


if (!is_dir($basepath))
    mkdir($basepath);

$fullpath = $basepath . '/' . $lang;



$guzzleClient = new GuzzleHttp\Client(['verify' => false]);

// instantiates a client
$client = new TextToSpeechClient();


// sets text to be synthesised
$synthesisInputText = (new SynthesisInput())
        ->setSsml($string);


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

if (file_put_contents($fullpath . "/" . $filename, $audioContent)) {

    //update db if file successfully generated
    $updateRes = $media->audioMovie_INSERT($language_grid_id, $filename);

    if ($updateRes)
        echo json_encode(array("response" => true, "state" => 'success', "message" => 'Audio content written as ' . $filename));
} else
    echo json_encode(array("response" => true, "state" => 'fail', "message" => 'No audio was generated'));