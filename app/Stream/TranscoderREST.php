<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



class TranscoderREST
{


    public static function process($id, $name, $type, $project, $content)
    {


        $ch = curl_init();



        $data = array("id" => $id, "name" => $name,"projectName" => $project, "mediaType" => $type, "contentId" => $content );

        $post = json_encode($data);


        $header = [
            'Content-Type: application/json'
        ];


        curl_setopt($ch, CURLOPT_URL, "http://video.nts.nl:8000/?process=all");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $server_output = curl_exec($ch);


        curl_close($ch);

        return $server_output;
    }

}



//$call = new TranscoderREST();
//
//
//if($call::process(172, "media.mp4","video/mp4", "P010538", 109))
//    echo "OK";
//else echo "NOTHING";


