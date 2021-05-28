<?php

/*****
 *
 *
 * 1 . write to temp disk
 * 2 Cut from file in temp
 * 3 write new file
 * 4 If all well, remove temp
 * 5 Return true
 *
 *
 */
class Process
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function cut_segment($start, $end, $output)
    {
        if (!file_exists($this->file)) {
            echo "File not found";
            return false;
        }

        //$command = "ffmpeg -i $this->file -ss " . $start . " -t " . $end . " -async 1 $output";

	$command = "ffmpeg -t " . $end . " -i $this->file -async 1 -ss " . $start . " $output";

	//$command = "ffmpeg -t 1:00 -i input.mpg -ss 45 output.mpg"
        exec($command);
        //        $errors = array();
//        exec("ffmpeg -i $this->file -ss ".$start." -t ".$end." -async 1 $output error 2>&1", $errors);
//        for debug
//        foreach($errors as $next) {
//            //handle error
//            echo $next;
//        }
        return true;
    }

    public function is_valid_time($time)
    {
        $case_hour = '/^(?:[01][0-9]|2[0-3]):[0-5][0-9]/';
        if (!preg_match($case_hour, $time))
            return false;
        return true;
    }

    public function format_time($time)
    {
        return '00:' . $time;
    }

}
