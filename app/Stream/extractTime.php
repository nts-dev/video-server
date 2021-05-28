<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$line_new = array();

define('ROOTPATH', dirname(__FILE__));
define("LOCAL_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("SERVER_NAME", $_SERVER["SERVER_NAME"]);

$ROOT_PATH = "";

if (SERVER_NAME === '192.168.1.2')
    $ROOT_PATH = LOCAL_PATH_ROOT . '/Training';
else
    $ROOT_PATH = LOCAL_PATH_ROOT;

//createEmptyImage(); exit;

$getfilename = $_GET['file'];
$media_path = $ROOT_PATH . '/uploads/' . $getfilename;

//echo $ROOT_PATH; exit;


if (file_exists($media_path)) {
    $path_info = pathinfo($media_path);

    //define file constants
    define("FILE_TYPE", $path_info['extension']);
    define("FILE_NAME", $path_info['filename']);

    $vttfile_path = $ROOT_PATH . '/uploads/overlays/' . FILE_NAME . '/caption1.vtt';

    if (file_exists($vttfile_path))
        send_reformatted($vttfile_path);
    else
        echo json_encode(array("response" => false, "message" => 'No subtitle file', "call" => 1));
} else {
    echo json_encode(array("response" => false, "message" => 'No dir'));
}

function send_reformatted($vtt_file) {
    global $line_new;
    // Add these headers to ease saving the output as text file


    $f = fopen($vtt_file, "r");


    $reg = '/(.{0,}[0,1]{0,}\s{0,}[0-9]{0,}.{0,}[0-9]+[0-9]+:[0-9]{0,}.{0,})/';


    $time_key = '';
    while ($line = fgets($f)) {

        $subst = '';
        if (preg_match($reg, $line, $match)) {
            if (strpos($match[1], "->") !== false) {

                $arr = explode("-", $match[1], 2);
                $fulltime = $arr[0];
                $line_new[$fulltime]['timestamp'] = $fulltime;
                $time_key = $fulltime;


                $dt = new DateTime("1970-01-01 $time_key", new DateTimeZone('UTC'));
                $seconds = (int) $dt->getTimestamp();
                $line_new[$fulltime]['seconds'] = $seconds;
            }
        } elseif (trim(preg_replace($reg, $subst, $line))) {
            # code...
            $line_new[$time_key]['subtext'] = $line;
        }
    }



    fclose($f);
}

//echo "<pre>";
//print_r($line_new);
//exit;


$i = 0;
$length = count($line_new);


foreach ($line_new as $key => $value) {
    # code...
    if ($key && $value['seconds'] > 0) {

        if (FILE_TYPE === 'mp3') {
            $filename = '/pic' . $i . '.jpg';
            $image_filepath_dir = $ROOT_PATH . '/uploads/thumbnails/' . FILE_NAME ;
            $image_filepath = $image_filepath_dir .$filename;
            
//   echo $image_filepath; exit;
            
            if (!is_dir($image_filepath_dir))
                mkdir($image_filepath_dir, 0777, true);
            
            createEmptyImage($filename, $image_filepath_dir);
        } else {
            $filename = FILE_NAME . '/pic' . $value['seconds'] . '.jpg';
            $image_filepath = $ROOT_PATH . '/uploads/thumbnails/' . $filename;
        }


        if (file_exists($image_filepath)) {
            if (isset($value['subtext'])) {
                $subtitleText = $value['subtext'];

                $subtitleString = wordwrap($subtitleText, 35, "\n", true);

                saveImageWithText($subtitleString, $image_filepath, $image_filepath);
                if ($i === $length - 3) {
                    echo json_encode(array("response" => true, "message" => 'Success', "call" => 0));
                }
            }
        } else {
            echo json_encode(array("response" => false, "message" => 'No ' . $filename . ' thumb')); exit;
        }
    }
    $i++;
}

function saveImageWithText($text, $source_file, $filename) {
    global $ROOT_PATH;

    // Copy and resample the imag
    list($width, $height) = getimagesize($source_file);
    $image_p = imagecreatetruecolor($width, $height);
    $image = imagecreatefromjpeg($source_file);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

    // Prepare font size and colors
    $text_color = imagecolorallocate($image_p, 0, 0, 0);
    $bg_color = imagecolorallocate($image_p, 255, 255, 255);

    $font = $ROOT_PATH . '/view/css/fonts/arial.ttf';

    $font_size = 9;

    // Set the offset x and y for the text position
//    $offset_x = 3;
//    $offset_y = 90;

    // Get the size of the text area
//    $dims = imagettfbbox($font_size, 0, $font, $text);
//    $text_width = $dims[4] - $dims[6] + $offset_x;
//    $text_height = $dims[3] - $dims[5] + $offset_y;

    // Add text background
    imagefilledrectangle($image_p, 0, 70, 192, 98, $bg_color);

    // Add text
    imagettftext($image_p, $font_size, 0, 10, 80, $text_color, $font, $text);

    // Save the picture
    imagejpeg($image_p, $filename, 100);
    // Clear
    imagedestroy($image);
    imagedestroy($image_p);
}

;

function createEmptyImage($file, $url) {

    $img = imagecreatetruecolor(192, 108);
    $bg = imagecolorallocate($img, 0, 0, 0);
    imagefilledrectangle($img, 0, 0, 192, 108, $bg);
    imagejpeg($img, $url . "/".$file, 100);
}
