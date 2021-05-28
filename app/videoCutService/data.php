<?php
//header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$action = $_GET['action'];

require_once('Video.php');
require_once('Process.php');
include_once('../Stream/config/database.php');
include_once('DataUtil.php');

$database = new AppDatabase();
$connection = $database->getConnection();
$file = new video($connection);
$dataUtil = new DataUtil();

$rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/';


$allowedFileExtensions = array('mp3', 'mp4', 'webm');
$response = array('Action success', 'Action fail');

const DIRECTORY = 1;
const EXTENSION = 0;
const MOVE_FILE = 2;
switch ($action) {
    case 1:
        $ERRORS = array();

        $name = filter_input(INPUT_GET, "name", FILTER_SANITIZE_STRING);
        $start_time = filter_input(INPUT_GET, "start", FILTER_SANITIZE_STRING);
        $end_time = filter_input(INPUT_GET, "end", FILTER_SANITIZE_STRING);
        $project_map_id = filter_input(INPUT_GET, "project", FILTER_SANITIZE_NUMBER_INT);

        if (!$name || !$start_time || !$end_time || !$project_map_id) {
            $error = "Some parameters not defined: please check again";
            $ERRORS[] = $error;
        }

        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value));
            die();
        }

        $name = str_replace('|', '', $name);
        $name = strtolower(str_replace(' ', '_', $name));


        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));


        $uploadFileDir = '../../uploads/cuts/temp/';

        $message = "";


        //configure file details
        $file->set_name($name);


        //Not allowed extensions
        if (in_array($fileExtension, $allowedFileExtensions) === false) {
            $error = "This is not a playable media file";
            $ERRORS[] = $error;
        }


        /**
         * Convert all media extensions that are not of type mp3 to mp4. This update is Subject to change.
         * Requested by kisuk
         *
         * Just override the fileExtension store
         **/
        $fileExtension = $fileExtension == "mp3" ? "mp3" : "mp4";


        //Temp directory dont exit
        if (is_dir($uploadFileDir) === false) {
            $error = "Temp directory not found";
            $ERRORS[] = $error;
        }


        //die executions here once there are errors
        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value, "extra" => array("info" => $value)));
            die();
        }


        //File is guaranteed
        if (empty($ERRORS))
            move_uploaded_file($fileTmpPath, "$uploadFileDir" . $fileName);
        else {
            $error = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            $ERRORS[] = $error;
            //echo $message;
        }

        /****
         *
         *
         * All is ok
         * we can configure the rest of file, validate time if incorrect format,
         * if not put in correct format & cut the video segment
         *
         * @Process is an object that formats & validates time, and run ffmpeg
         */

        $new_file_name = "";
        $destination = '../../uploads/cuts/';

        if (!is_dir($destination)) {
            $error = "Directory not found";
            $ERRORS[] = $error;
        }


        $process = new Process("$uploadFileDir" . $fileName);
        $entry_time_start = $process->format_time($start_time);
        $entry_time_end = $process->format_time($end_time);


        if (!$process->is_valid_time($entry_time_start)
            && !$process->is_valid_time($entry_time_end)) {
            $error = "Either start or end time is not valid";
            $ERRORS[] = $error;
        }


        //configure file details and push object to database

        $file->set_start($entry_time_start);
        $file->set_end($entry_time_end);
        $file->set_ext($fileExtension);
        $file->set_map($project_map_id);


        //die executions here again once there are errors
        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value, "extra" => array("info" => $value)));
//                echo json_encode(array("state" => false, "message" => $value));
            die();
        }


        $file->set_url($url . 'uploads/cuts');


        if (empty($ERRORS) && $file->db_put_metadata()) {
            $result = $file->db_get_last_insert();
            $row = $result->fetch();


            $new_file_name = $row['file_name'] . "." . $fileExtension;

        } else {
            $error = "File renaming error";
            $ERRORS[] = $error;
        }


        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value, "extra" => array("info" => $value)));
            die();
        }


        $uploaded = false;
        if (empty($ERRORS) && $process->cut_segment($entry_time_start, $entry_time_end, $destination . $new_file_name))
            echo json_encode(array("state" => true, "message" => "Upload complete"));
        else {
            $error = "File transcoding error";
            echo json_encode(array("state" => false, "message" => $error));
        }


        $temp = $uploadFileDir . $fileName;


        //Gain permission and remove temp file
        chown($temp, 666);
        unlink($temp);
        break;
    case 2:
        $project_id = filter_input(INPUT_GET, "project", FILTER_SANITIZE_NUMBER_INT);

        $result = $file->db_get_metadata($project_id);
        $dataUtil->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $id . " ' >";
                echo "<cell><![CDATA[" . $id . "]]></cell>";
                echo "<cell><![CDATA[" . $file_name . "]]></cell>";
                echo "<cell><![CDATA[" . $start_time . "]]></cell>";
                echo "<cell><![CDATA[" . $end_time . "]]></cell>";
                echo "<cell><![CDATA[" . $link . "]]></cell>";
                echo "<cell><![CDATA[" . $update . "]]></cell>";
                echo "</row>";
            }
        }
        echo "</rows >";
        break;

    case 3:
        $response = array('Action success', 'Action fail');
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);


        $result = $file->db_delete_record($id);
        if ($result) echo json_encode(array("state" => true, "message" => $response[0]));
        else echo json_encode(array("state" => true, "message" => $response[1]));

        break;

    case 4:
        $ERRORS = array();

        $name = filter_input(INPUT_GET, "name", FILTER_SANITIZE_STRING);
        $project_id = filter_input(INPUT_GET, "project", FILTER_SANITIZE_NUMBER_INT);

        if (!$name || !$project_id) {
            $error = "Some parameters not defined: please check again";
            $ERRORS[] = $error;
        }

        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value));
            die();
        }

        $name = str_replace('|', '', $name);
        $name = strtolower(str_replace(' ', '_', $name));


        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));


        $uploadFileDir = '../../uploads/cuts/' . $name;

        $message = "";


        //configure file details
        $file->set_name($name);


        //Not allowed extensions
        if (in_array($fileExtension, $allowedFileExtensions) === false) {
            $error = "This is not a playable media file";
            $ERRORS[] = $error;
        }


        /**
         * Convert all media extensions that are not of type mp3 to mp4. This update is Subject to change.
         * Requested by kisuk
         *
         * Just override the fileExtension store
         **/
        $fileExtension = $fileExtension == "mp3" ? "mp3" : "mp4";


        //die executions here once there are errors
        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value, "extra" => array("info" => $value)));
            die();
        }


        //Temp directory dont exit
        if (is_dir($uploadFileDir) === false) {
            mkdir($uploadFileDir, 0700); // create a new dir if not existing yet
            mkdir($uploadFileDir . "/chunks", 0700);
        }


        /**
         * Get current parent file count
         *
         */


        $sort_result = $file->db_get_sort($project_id, 0);
        $row = $sort_result->fetch();
        $currentsortcount = $row['sort_id'] + 1;


        $FILE_REAL_NAME = $name . "_" . $currentsortcount;
        $FILE_ALIAS = $FILE_REAL_NAME . "." . $fileExtension;
        $NEW_PATH = $uploadFileDir . "/" . $FILE_ALIAS;


        /*
         *
         *Configure file
         */
        $file->set_url($url . 'uploads/cuts/' . $name . '/' . $FILE_ALIAS);
        $file->set_ext($fileExtension);
        $file->set_map($project_id);
        $file->set_sort($currentsortcount);
        $file->set_name($FILE_REAL_NAME);
        $file->set_start("00:00:00.00");
        $file->set_end("00:00:00.00");  //start & end have No use with parent item


        //File is guaranteed
        if (empty($ERRORS) && $file->db_put_metadata_parent(0)) {
            try {
                move_uploaded_file($fileTmpPath, $NEW_PATH);
                echo json_encode(array("state" => true, "message" => "Upload complete"));
            } catch (Exception $e) {
                $ERRORS[] = $e;

            }
        } else {
            $error = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            $ERRORS[] = $error;
            //echo $message;
        }


        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value));
            die();
        }
        break;

    case 5:

        $ERRORS = array();


        $start_time = filter_input(INPUT_POST, "start", FILTER_SANITIZE_STRING);
        $end_time = filter_input(INPUT_POST, "end", FILTER_SANITIZE_STRING);
        $parent_id = filter_input(INPUT_POST, "parent", FILTER_SANITIZE_NUMBER_INT);
        $project_id = filter_input(INPUT_POST, "project", FILTER_SANITIZE_NUMBER_INT);

        $client_parent_url = filter_input(INPUT_POST, "url", FILTER_SANITIZE_STRING);
        $client_parent_name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);


        /**
         *
         * Strip parent extension from parent url
         *
         */

        $parent_file_extension = ".mp4";
        $last_index = strlen($client_parent_url) - 1;

        while (!ctype_punct($client_parent_url{$last_index}))
            $last_index--;

        $parent_file_extension = substr($client_parent_url, $last_index);


        /**
         *
         * Remove end numeric from client name
         *
         */

        $length = strlen($client_parent_name) - 1;
        $count = $length;


        while (ctype_digit($client_parent_name{$count}))
            $count--;


        $end = ($length - $count) + 1;
        $folder_name = substr($client_parent_name, 0, -$end);
        $parent_name = $folder_name . "/" . $client_parent_name . $parent_file_extension;


//        echo $parent_name; exit;


        if (!$start_time || !$end_time || !$parent_id || !$parent_name) {
            $error = "Some parameters not defined: please check again";
            $ERRORS[] = $error;
        }

        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value));
            die();
        }

        $LOCAL_DIR = '../../uploads/cuts/';
        $LOCAL_FILE = $LOCAL_DIR . $parent_name;


        $LOCAL_FILE_INFO = pathinfo($LOCAL_FILE);

        if (!$LOCAL_FILE_INFO) {
            echo json_encode(array("state" => false, "message" => "File not found"));
            die();
        }

        $parent_file_extension = "." . $LOCAL_FILE_INFO['extension'];
        $parent_file = $LOCAL_FILE_INFO['basename'];
        $parent_file_dir = $LOCAL_FILE_INFO['dirname'];
        $parent_file_name = substr($parent_file, 0, -strlen($parent_file_extension));


        $message = "";


        /**
         * Get current parent file count
         *
         */


        $sort_result = $file->db_get_sort($project_id, $parent_id);
        $row = $sort_result->fetch();
        $currentsortcount = $row['sort_id'] + 1;


        $uploadFileDir = $parent_file_dir . '/chunks/';

        $FILE_REAL_NAME = $parent_file_name . "_split[" . $currentsortcount . "]";
        $FILE_ALIAS = $FILE_REAL_NAME . $parent_file_extension;
        $NEW_PATH = $uploadFileDir . "/" . $FILE_ALIAS;
        $FILE_URL = $url . "uploads/cuts/" . $folder_name . '/chunks/' . $FILE_ALIAS;


        $process = new Process($LOCAL_FILE);
        $entry_time_start = $process->format_time($start_time);
        $entry_time_end = $process->format_time($end_time);


        if (!$process->is_valid_time($entry_time_start)
            && !$process->is_valid_time($entry_time_end)) {
            $error = "Either start or end time is not valid";
            $ERRORS[] = $error;
        }


        /*
       *
       *Configure file
       */
        $file->set_url($FILE_URL);
        $file->set_ext($parent_file_extension);
        $file->set_map($project_id);
        $file->set_sort($currentsortcount);
        $file->set_name($FILE_REAL_NAME);
        $file->set_start($entry_time_start);
        $file->set_end($entry_time_end);


        $new_file_destination = $uploadFileDir . $FILE_REAL_NAME . $parent_file_extension;

//        echo $new_file_destination; exit;

        if (empty($ERRORS) && is_dir($uploadFileDir))
            try {
                $process->cut_segment($entry_time_start, $entry_time_end, $new_file_destination);
            } catch (Exception $e) {
                $ERRORS[] = $e;
            }


        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value));
            die();
        }

        if (empty($ERRORS) && $file->db_put_metadata_parent($parent_id))
            echo json_encode(array("state" => true, "message" => "Upload complete"));
        else {
            $error = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            $ERRORS[] = $error;
        }


        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value));
            die();
        }


        break;

    case 6:
        $project_id = filter_input(INPUT_GET, "project", FILTER_SANITIZE_NUMBER_INT);

        $videos = array();


        $result = $file->db_get_metadata_parent_child($project_id);


        if ($result->rowCount() > 0) {

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);


                if ($row['parent_parent_id'] == 0) {
                    $videos[$parent_id]['id'] = $parent_id;
                    $videos[$parent_id]['parent_name'] = $parent_name;
                    $videos[$parent_id]['parent_link'] = $parent_link;
                    $videos[$parent_id]['children_count'] = $children_count;
                }
                if (isset($child_id) && $child_parent_id > 0) {
                    $videos[$parent_id]['children'][$child_id]['child_id'] = $child_id;
                    $videos[$parent_id]['children'][$child_id]['child_name'] = $child_name;
                    $videos[$parent_id]['children'][$child_id]['child_start'] = $child_start;
                    $videos[$parent_id]['children'][$child_id]['child_end'] = $child_end;
                    $videos[$parent_id]['children'][$child_id]['child_link'] = $child_link;
                    $videos[$parent_id]['children'][$child_id]['child_updated'] = $child_updated;
                }

            }
        }


        $dataUtil->setXMLHeader();
        echo "<rows >";
        foreach ($videos as $key => $video) {
            echo "<row id = 'p_" . $video['id'] . "' >";
            echo "<cell><![CDATA[" . $video['parent_name'] . "]]></cell>";
            echo "<cell><![CDATA[" . $video['parent_link'] . "]]></cell>";

            if (isset($video['children']))
                foreach ($video['children'] as $child) {
                    echo "<row id = '" . $child['child_id'] . " ' >";
                    echo "<cell><![CDATA[" . $child['child_name'] . "]]></cell>";
                    echo "<cell><![CDATA[" . $child['child_link'] . "]]></cell>";
                    echo "<cell><![CDATA[" . $child['child_start'] . "]]></cell>";
                    echo "<cell><![CDATA[" . $child['child_end'] . "]]></cell>";
                    echo "<cell><![CDATA[" . $child['child_updated'] . "]]></cell>";
                    echo "</row>";
                }
            echo "</row>";
        }
        echo "</rows>";

        break;

    case 7:
        /*
         *
         * @param is_parent is of [0. 1] :: parent = 0, else child
         *
         * if parent, delete children too
         *
         * else delete child
         */

        $file_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
        $file_is_parent = filter_input(INPUT_GET, "is_parent", FILTER_SANITIZE_NUMBER_INT);
        $file_url = filter_input(INPUT_GET, "url", FILTER_SANITIZE_STRING);


        if (!$file_id || !$file_url) {
            json_encode(array("state" => true, "message" => $response[1] . ". No file found"));
            die();
        }

        /*
         * Ids_to_delete :: Stores the id of the files to be deleted
         *
         * Add the incoming file_id to the array by default regardless of parent or child
         *
         */
        $ids_to_delete = array();
//        $ids_to_delete[] = $file_id;

        /*
         *
         * Strip extension from file url :: If is_parent, Assuming all children/descendants have same extension
         *
         */
//
        $file_extension = ".mp4";
        $last_index = strlen($file_url) - 1;

        while (!ctype_punct($file_url{$last_index}))
            $last_index--;

        $file_extension = substr($file_url, $last_index);


        /*
         *
         * unlink file
         *      if success, remove record
         *
         */

        $file_directory = "../../" . substr($file_url, strpos($file_url, "upload"));

        $chunk = "chunks";
        $end = (strpos($file_directory, $chunk));
        $directory_name = substr($file_directory, 0, ($end + strlen($chunk) + 1));


        /*
         *
         * 1. Get children from db and put into a dictionary [parent_children]
         *
         * 2. if directory_name is dir.. :: open and compare
         *
         * We will compare if directory item is present in [parent_children]
         *      if matches, delete
         */

        if ($file_is_parent == 0) {
            $children_result = $file->db_get_children($file_id);
            $parent_children = array();


            if ($children_result->rowCount() > 0)
                while ($row = $children_result->fetch(PDO::FETCH_ASSOC)) {
                    $parent_children[] = $row['file_name'] . $file_extension;
//                    $ids_todelete[] =
                }


//            $result = "";

            if (is_dir($directory_name)) {
                if ($dh = opendir($directory_name)) {
                    while (($file = readdir($dh)) !== false)
                        if (in_array($file, $parent_children) && unlink_file($directory_name . $file)) //  file is in parent_children
                            $result = $file->db_delete_record($file_id);
                    closedir($dh);
                }
            }

        }


        /*
         * Now delete parent
         */
        if (file_exists($file_directory))
            try {
                chown($file_directory, 666);
                unlink($file_directory);
                $result = $file->db_delete_record($file_id);

                if ($result) echo json_encode(array("state" => true, "message" => $response[0]));
                else echo json_encode(array("state" => true, "message" => $response[1]));

            } catch (Exception $exception) {
                json_encode(array("state" => false, "message" => $exception));
            }
        else json_encode(array("state" => true, "message" => $response[1]));


        break;
}


function unlink_file($file_directory)
{

    if (file_exists($file_directory) && chown($file_directory, 666)) {   //file exist nd Gain permission , then remove file
        try {
            unlink($file_directory);
        } catch (Exception $e) {
            json_encode(array("state" => false, "message" => $e));
            return false;
        }
    }

    return true;
}




