<?php

include_once 'config/core.php';


date_default_timezone_set('Etc/UTC');
$action = $_GET['action'];

include_once 'config/database.php';

include_once 'media.php';
include_once 'TranscoderREST.php';

$database = new AppDatabase();

$db = $database->getConnection();

$media = new Media($db);

$data = "";


//session_start();
$allowedFileExtensions = array('mp3', 'mp4', 'webm');
switch ($action) {
    default:


        $result = $media->login(
            filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'graDuration', FILTER_SANITIZE_STRING)
        );
        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            session_start();
            echo json_encode(array("response" => true, "state" => 'success', "userId" => $row['contact_id'], "username" => $row['contact_attendent']));
            $_SESSION['user_session'] = $row['contact_id'];
            $_SESSION['user_name'] = $row['contact_attendent'];
            $_SESSION['user_br'] = $row['branch_id'];
        } else {
            echo json_encode(array("response" => false, "state" => 'fail', "message" => 'ID or password does not exist'));
        }
        break;

    case 1:

        $result = $media->getMedia(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        $row = $result->fetch();
        if ($result) {
            echo json_encode(array("response" => true, "file" => $media->ecryption_ACTIONS('encrypt', $row['Alias']), "track" => 1));
        } else {
            echo json_encode(array("response" => false, "file" => 'none', "track" => 0));
        }


        break;

    case 2:
        session_start();
        $result = $media->courses();
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $ID . "' >";
                echo "<cell> " . $ID . "</cell>";
                echo "<cell><![CDATA[" . $Subject . "]]></cell>";
                echo "<cell><![CDATA[" . $Duration . "]]></cell>";
                echo "<cell><![CDATA[" . $Description . "]]></cell>";
                echo "</row>";
            }
        }
        echo "</rows >";
        break;

    case 3:
        $result = $media->contents(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $ID . "' >";
                echo "<cell><![CDATA[" . $ID . "]]></cell>";
                echo "<cell><![CDATA[" . $Sort . "]]></cell>";
                echo "<cell><![CDATA[" . $ModuleName . "]]></cell>";
                echo "<cell><![CDATA[" . $date_updated . "]]></cell>";
                echo "<cell><![CDATA[" . $Description . "]]></cell>";
                echo "</row>";
            }
        } else {
            echo "<row id ='no_1' >";
            echo "<cell></cell>";
            echo "<cell></cell>";
            echo "<cell>No module exist</cell>";
            echo "</row>";
        }
        echo "</rows >";
        break;

    case 4:
        $result = $media->course_ADD(10398, filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Added'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Inserting'));
        }
        break;

    case 5:
        $result = $media->course_DELETE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Deleted'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While deleting'));
        }
        break;

    case 6:
        $result = $media->course_UPDATE(
            filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'field', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING)
        );
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Deleted'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While deleting'));
        }
        break;

    case 7:
        $project = array();
        $media_files = array();
        $result = $media->course_ONE(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        //        $result = $media->course_ONE('10402');
        $media->setXMLHeader();
        echo "<rows>";


        if ($result->rowCount() > 0) {
            $row = $result->fetch();

            $mediares = $media->getModules($row['id']);

            if ($mediares->rowCount() > 0) {
                while ($row1 = $mediares->fetch(PDO::FETCH_ASSOC)) {
                    $project[$row1['ModuleID']]['ID'] = $row1['ModuleID'];
                    $project[$row1['ModuleID']]['NAME'] = $row1['ModuleName'];
                    $media_files[$row1['ModuleID']][$row1['mediaId']] = $row1;
                }
            }
        }

        foreach ($project as $content) {
            if (isset($content['ID'])) {
                echo "<row id_r = '" . $content['ID'] . "' >";
                echo "<cell><![CDATA[" . $content['NAME'] . "]]></cell>";

                foreach ($media_files[$content['ID']] as $key => $value) {
                    if (isset($value['mediaId'])) {
                        echo "<row id = '" . $value['mediaId'] . "' >";
                        //                        echo "<cell></cell>";
                        echo "<cell><![CDATA[" . $value['FileName'] . "]]></cell>";
                        echo "</row>";
                    }
                }

                echo "</row>";
            }
        }
        echo "</rows>";

        //        echo '<pre>';
        //        print_r($project);
        break;

    case 8:
        $object = new stdClass();
        $object->Subject = filter_input(INPUT_POST, 'Subject', FILTER_SANITIZE_STRING);
        $object->Description = filter_input(INPUT_POST, 'Description', FILTER_SANITIZE_STRING);
        $object->Duration = filter_input(INPUT_POST, 'Duration', FILTER_SANITIZE_NUMBER_INT);
        $object->Comment = filter_input(INPUT_POST, 'Comment', FILTER_SANITIZE_STRING);

        $count = 0;
        foreach ($object as $field => $value) {
            $result = $media->course_UPDATE(filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT), $field, $value);
            $count = $count + 1;
        }
        if ($count > 3) {
            echo json_encode(array("response" => true, "message" => 'Record Updated'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While updating'));
        }
        break;

    case 9:
        $result = $media->content_ADD(filter_input(INPUT_POST, 'course', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Added'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Inserting'));
        }
        break;

    case 10:
        $result = $media->content_DELETE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Deleted'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While deleting'));
        }
        break;

    case 11:
        $result = $media->content_ONE(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $media->setXMLHeader();
        echo "<data>";
        foreach ($row as $field => $value) {
            echo '<' . htmlspecialchars($field) . '>' . htmlspecialchars($value) . '</' . htmlspecialchars($field) . '>';
            echo $data;
        }
        echo "</data>";
        break;

    case 12:
        $result = $media->content_UPDATE(
            filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'ModuleName', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'Description', FILTER_SANITIZE_STRING)
        );
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Added'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Inserting'));
        }
        break;

    case 13:

        ini_set("display_errors", 1);
        ini_set('max_execution_time', 300);
        ini_set('max_input_time', 300);
        ini_set('upload_max_filesize', '0');
        ini_set('post_max_size', '0');
        //upload files
        $templocation = $_FILES["file"]["tmp_name"];
        $filename = $_FILES["file"]["name"];


        $file_type = $_FILES["file"]['type'];
        $file_size = $_FILES["file"]["size"];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

        $ERRORS = array();

        $content = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $action = filter_input(INPUT_GET, 'kind', FILTER_SANITIZE_STRING);
        $project = generateProjectId(filter_input(INPUT_GET, 'project', FILTER_SANITIZE_NUMBER_INT));


        //Not allowed extensions
        if (in_array($file_ext, $allowedFileExtensions) === false || substr_count($filename, ".") > 1) {
            $error = "This is not a playable media file";
            $ERRORS[] = $error;
        }

        //die executions here once there are errors
        if (!empty($ERRORS)) {
            foreach ($ERRORS as $value)
                echo json_encode(array("state" => false, "message" => $value));
            die();
        }


        $transcoder = new TranscoderREST();

        $ROOT_PATH = "../../mediaresources/";


        $last_sort_result = $media->media_LAST_SORT($content);
        if ($last_sort_result) {
            $sort = $last_sort_result->fetchColumn();
            if ($sort)
                $sort = $sort + 1;
            else
                $sort = 1;

            if ($action == 'new' && $media->media_INSERT($content, $filename, $media->defaultDate(),
                    $file_size, $file_type, $sort, 'pXOH4kUIWyguRcQqnwi0f6NsqN62')) {


                $id = $media->lastInsertMediaFile;
                $to_file_path = $project . "/" . $content . "/" . getMediaType($file_type) . "/" . $id . "/";
                $target_path = $ROOT_PATH . $to_file_path;


                $new_file = $target_path . "/media." . $file_ext;

                if (mkdir($target_path, 0700, true) && move_uploaded_file($templocation, $new_file)) {

                    //call transcoder service
                    // $transcoder::process($id, "media." . $file_ext, $file_type, $project, $content);
                    print_r("{state: true, message:'File uploaded'}");

                } else
                    print_r("{state:false, message:'Error while updating'}");


            } else {

                $file_id = filter_input(INPUT_GET, 'media', FILTER_SANITIZE_NUMBER_INT);
                if ($media->media_REPLACE($content, $filename, $media->defaultDate(),
                    $filename, $file_size, $file_type, $file_id)) {

                    $to_file_path = $project . "/" . $content . "/" . getMediaType($file_type) . "/" . $file_id . "/";
                    $target_path = $target_path = $ROOT_PATH . $to_file_path;

                    $new_file = $target_path . "/media." . $file_ext;

                    if (move_uploaded_file($templocation, $new_file)) {


                        //call transcoder service
                        // $transcoder::process($file_id, "media." . $file_ext, $file_type, $project, $content);
                        print_r("{state: true, message:'File uploaded'}");


                    } else
                        print_r("{state:false, message:'Error while updating'}");
                }

            }
        }

        break;

    case 14:
        $result = $media->media_files(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $ID . "' >";
                echo "<cell><![CDATA[" . $ID . "]]></cell>";
                echo "<cell><![CDATA[" . $Sort . "]]></cell>";
                echo "<cell><![CDATA[" . $uploadDate . "]]></cell>";
                echo "<cell><![CDATA[" . $FileName . "]]></cell>";
                echo "<cell><![CDATA[" . $Alias . "]]></cell>";
                echo "<cell><![CDATA[" . $fileSize . "]]></cell>";
                echo "<cell><![CDATA[" . $fileType . "]]></cell>";
                echo "<cell><![CDATA[" . $hash . "]]></cell>";
                echo "</row>";
            }
        } else {
            echo "<row id ='no_1' >";
            echo "<cell></cell>";
            echo "<cell></cell>";
            echo "<cell>No media exists</cell>";
            echo "</row>";
        }
        echo "</rows >";
        break;

    case 15:
        $filename = $media->media_files_FILENAME(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));

        if (!$filename)
            die();

        $file = '../../uploads/' . $filename; //tobe uploaded

        $result = $media->media_files_DELETE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Deleted'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While deleting'));
        }

        if (file_exists($file))
            unlink($file);


        break;

    case 16:
        // Entry point of QTI results api
        $apiUrl = 'http://213.201.143.88/tao/taoResultServer/QtiRestResults';

        // Parameters required to proceed request, take care to escape uri variable
        $testtakerUri = urlencode('http://213.201.143.88/tao/NTS_tao.rdf#i1536412367741377');
        $deliveryUri = urlencode('http://213.201.143.88/tao/NTS_tao.rdf#i1536412958168293');

        // Case A: Result id for delivery execution
        $resultId = urlencode('http://213.201.143.88/tao/NTS_tao.rdf#i1536413116302294');
        // Case B: Result id for LTI delivery.
        //        $resultId = 'bf29e71611330b19a723e2bed6f47255';
        // Initialize the cURL request to get the latest results for a given test-taker and delivery
        //        $process = curl_init($apiUrl . '/getLatest?testtaker=' . $testtakerUri . '&delivery=' . $deliveryUri);
        // OR Initialize the cURL request to get a specific result (by default the result identifier is the same as the delivery execution identifier)
        $process = curl_init($apiUrl . '/getQtiResultXml?delivery=' . $deliveryUri . '&result=' . $resultId);
        // Call api with HTTP GET method
        curl_setopt($process, CURLOPT_HTTPGET, 1);

        // Choose your output, QTI data is based on XML
        curl_setopt($process, CURLOPT_HTTPHEADER, array("Accept: application/xml"));

        // Get response as a string instead of output it directly
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($process, CURLOPT_REFERER, true);

        // Set up your TAO credential
        curl_setopt($process, CURLOPT_USERPWD, "kisuk:kisuk@1234");

        // Proceed the curl request
        $data = curl_exec($process);


        // REST communicate through HTTP code, take care of it
        $httpCode = curl_getinfo($process, CURLINFO_HTTP_CODE);
        echo $httpCode;
        // Close process handling
        curl_close($process);
        break;

    case 17:
        $result = $media->media_files_UPDATE(
            filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), $_POST['text']
        );
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Added'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Inserting'));
        }
        break;

    case 18:
        $result = $media->media_file(
            filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT)
        );
        if ($result) {
            $row = $result->fetch();

            echo json_encode(array("response" => true, "content" => $row['comments'], "filename" => $row['FileName']));
        }
        break;

    case 19:

        $result = $media->courses(1, ($_SESSION['user_br']) ? $_SESSION['user_br'] : 1);


        $media->setXMLHeader();
        echo '<tree id="0">';
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                if (!isset($objects[$row['id']])) {
                    $objects[$row['id']] = new stdClass;
                    $objects[$row['id']]->children = array();

                    $obj = $objects[$row['id']];
                    $obj->id = $row['id'];
                    $obj->name = $row['project_name'];
                    $obj->parent_id = $row['parent_id'];
                    if ($eid == 24743) {
                        if ($row['id'] == '461') {
                            $roots[] = $obj;
                        } else {
                            if (!isset($object[$row['parent_id']])) {
                                $object[$row['parent_id']] = new stdClass;
                                $object[$row['parent_id']]->children = array();
                            }

                            $objects[$row['parent_id']]->children[$row['id']] = $obj;
                        }
                    } else if ($eid == 25001) {
                        if ($row['id'] == '5092') {
                            $roots[] = $obj;
                        } else {
                            if (!isset($object[$row['parent_id']])) {
                                $object[$row['parent_id']] = new stdClass;
                                $object[$row['parent_id']]->children = array();
                            }

                            $objects[$row['parent_id']]->children[$row['id']] = $obj;
                        }
                    } else if ($eid == 22185) {
                        if ($row['id'] == '5344') {
                            $roots[] = $obj;
                        } else {
                            if (!isset($object[$row['parent_id']])) {
                                $object[$row['parent_id']] = new stdClass;
                                $object[$row['parent_id']]->children = array();
                            }

                            $objects[$row['parent_id']]->children[$row['id']] = $obj;
                        }
                    } else if ($eid == 1960) {
                        if ($row['id'] == '376') {
                            $roots[] = $obj;
                        } else {
                            if (!isset($object[$row['parent_id']])) {
                                $object[$row['parent_id']] = new stdClass;
                                $object[$row['parent_id']]->children = array();
                            }

                            $objects[$row['parent_id']]->children[$row['id']] = $obj;
                        }
                    } else if ($eid == 26907) {
                        if ($row['id'] == '2172') {
                            $roots[] = $obj;
                        } else {
                            if (!isset($object[$row['parent_id']])) {
                                $object[$row['parent_id']] = new stdClass;
                                $object[$row['parent_id']]->children = array();
                            }

                            $objects[$row['parent_id']]->children[$row['id']] = $obj;
                        }
                    } else {
                        if ($row['parent_id'] == 0) {
                            $roots[] = $obj;
                        } else {
                            if (!isset($object[$row['parent_id']])) {
                                $object[$row['parent_id']] = new stdClass;
                                $object[$row['parent_id']]->children = array();
                            }

                            $objects[$row['parent_id']]->children[$row['id']] = $obj;
                        }
                    }
                }
            }
        }

        $x = 0;
        foreach ($roots as $obj) {
            ++$x;
            printXML($obj, $x, $eid, true);
        }

        echo '</tree>';
        break;

    case 20:
        $result = $media->content_UPDATE_COMMENT(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), $_POST['text']);
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Added'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Inserting'));
        }
        break;

    case 21:
        $result = $media->content_ONE(
            filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT)
        );
        if ($result) {
            $row = $result->fetch();

            echo json_encode(array("response" => true, "content" => $row['comment']));
        }
        break;

    case 22:
        session_start();
        unset($_SESSION['user_session']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_br']);
        if (session_destroy()) {
            echo json_encode(array("response" => true, "state" => 'success'));
        }


        break;

    case 23:
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $data = $_POST['text'];
        $result = $media->media_file($id);
        if ($result) {
            $row = $result->fetch();
            $fileName = $row['FileName'];

            $fileName_srt = '../../uploads/' . $fileName . '.srt';
            //            $fileName_srt = '../../uploads/test.srt';

            $my_file = 'file.txt';
            $handle = fopen($fileName_srt, 'w') or die('Cannot open file:  ' . $fileName_srt);

            fwrite($handle, $data);
        }
        break;

    case 24:
        $result = $media->NTSLanguages();
        $num = $result->rowCount();
        $media->setXMLHeader();

        $string = '<toolbar>'
            . '<item type="button" id="add" text="Add Default Languages" img="fa fa-plus" /><item type="separator" id="sep_1" />'
            . '<item type="buttonSelect" id="language" text="New Language" img="fa fa-check-square"  openAll="true" renderSelect="true" mode="select">';
        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $string .= '<item type="button" id="' . $row['languages_id'] . '" text="' . $row['name'] . '" img="fa fa-check-square-o"/>';
            }
        }
        $string .= '</item><item type="separator" id="sep_2" />';
        $string .= '<item type="button" id="generate" text="  Generate File" img="fa fa-cog" /><item type="separator" id="sep_3" />'
            . '<item type="button" id="delete" text="Delete" img="fa fa-trash" /><item type="separator" id="sep_4" />'
            . '</toolbar>';
        echo $string;

        break;

    case 25:
        $result = $media->time_caption(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $time_ID . "' >";
                echo "<cell><![CDATA[" . $begin . "]]></cell>";
                echo "<cell><![CDATA[" . $end . "]]></cell>";
                echo "</row>";
            }
        }
        echo "</rows >";

        break;

    case 26:
        $result = $media->time_caption_INSERT(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Added'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Inserting'));
        }
        break;

    case 27:
        $result = $media->time_caption_UPDATE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'field', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Updated'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Updating'));
        }
        break;

    case 28:
        $result = $media->translations(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $ID . "' >";
                echo "<cell><![CDATA[" . $name . "]]></cell>";
                echo "<cell><![CDATA[" . $translations . "]]></cell>";
                echo "</row>";
            }
        } else {
            echo "<row id ='no_1' >";
            echo "<cell></cell>";
            echo "<cell>No translation</cell>";
            echo "</row>";
        }
        echo "</rows >";

        break;
    case 29:
        $result = $media->translations_INSERT_DEFAULTS(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Added'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While inserting'));
        }
        break;

    case 30:
        $result = $media->translations_UPDATE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'field', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING));
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record Updated'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While Updating'));
        }
        break;

    case 31:
        //        $fileResult = $media->media_file(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        $fileResult = $media->media_file(65);
        if ($fileResult) {
            $row = $fileResult->fetch();

            $fileExt = $row['FileName'];
            $filename = substr($fileExt, 0, -3);


            $result = $media->caption_generator(65);
            //            $result = $media->caption_generator(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
            $timeStamps = array();


            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    if (!isset($objects[$language])) {
                        $timeStamps[$language]['LANG_ID'] = $language;
                        $timeStamps[$language]['LANGUAGE'] = $language_name;

                        $timeStamps[$language]['FONT'] = $font;
                        $timeStamps[$language]['FONT_SIZE'] = $font_size;
                        $timeStamps[$language]['BACKGROUND'] = $background;
                        $timeStamps[$language]['COLOR'] = $color;
                        $timeStamps[$language]['TEXT_POSITION'] = $text_position;

                        $timeStamps[$language]['TIME_CAP'][$caption_Id]['TIME_CAP_ID'] = $time_ID;
                        $timeStamps[$language]['TIME_CAP'][$caption_Id]['BEGIN'] = $begin;
                        $timeStamps[$language]['TIME_CAP'][$caption_Id]['END'] = $end;
                        $timeStamps[$language]['TIME_CAP'][$caption_Id]['TRANSLATION'] = $translations;
                    }
                }
            }

            //            echo '<pre>';
            //            print_r($timeStamps); exit;

            foreach ($timeStamps as $language) {
                $str = 'WEBVTT ' . PHP_EOL;
                $str .= '' . PHP_EOL;
                if (isset($language['LANG_ID'])) {

                    $fileName_srt = '../../uploads/' . $filename . $language['LANG_ID'] . '.vtt';

                    //                    print_r($language['TIME_CAP']);
                    foreach ($language['TIME_CAP'] as $file) {
                        if (isset($file['TIME_CAP_ID'])) {
                            $str .= $file['BEGIN'] . ' --> ' . $file['END'] . '  ' . PHP_EOL;;
                            $str .= $file['TRANSLATION'] . '  ' . PHP_EOL;
                            $str .= '' . PHP_EOL;
                        }
                    }
                    $handle = fopen($fileName_srt, 'w') or die('Cannot open file:  ' . $fileName_srt);
                    fwrite($handle, $str);
                }
            }
        }

        break;

    case 32:
        $fileResult = $media->media_file(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($fileResult) {
            $row = $fileResult->fetch();

            $file = $row['FileName'];
            $filename = substr($file, 0, -3);

            $fileName_srt = '../../uploads/' . $filename . 'ttml';

            if (file_exists($fileName_srt)) {
                echo json_encode(array("response" => false, "status" => 0, "message" => 'Already exist. Regenerate this File?'));
            } else {
                echo json_encode(array("response" => true, "status" => 1, "message" => 'Continue'));
            }
        }
        break;

    case 33:
        $result = $media->subtitle_procedures_UPDATE($_POST['text']);
        if ($result) {
            echo json_encode(array("response" => true, "message" => 'Record updated'));
        } else {
            echo json_encode(array("response" => false, "message" => 'Error While updating'));
        }

        break;

    case 34:
        $result = $media->subtitle_procedures();
        if ($result) {
            $row = $result->fetch();
            echo json_encode(array("response" => true, "content" => $row['Procedure']));
        }
        break;

    case 35:
        $result = $media->content_SORTATION(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'nextId', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Record updated'));
        } else {
            echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Error while updating'));
        }

        break;

    case 36:
        $result = $media->media_SORTATION(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'nextId', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Record updated'));
        } else {
            echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Error while updating'));
        }
        break;

    case 37:
        $result = $media->subtitle_properties_INSERT(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'font', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'font_size', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'background', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'text_position', FILTER_SANITIZE_STRING));
        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Record updated'));
        } else {
            echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Error while updating'));
        }


        break;
    case 38:
        $result = $media->subtitle_properties(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $media->setXMLHeader();
        echo "<data>";
        foreach ($row as $field => $value) {
            echo '<' . htmlspecialchars($field) . '>' . htmlspecialchars($value) . '</' . htmlspecialchars($field) . '>';
            echo $data;
        }
        echo "</data>";
        break;

    case 39:
        $projectId = generateProjectId(filter_input(INPUT_GET, 'projectId', FILTER_SANITIZE_NUMBER_INT));
        $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);
        $file = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_NUMBER_INT);
        $filepath = 'https://video.nts.nl/mediaresources/' . $projectId . "/" . $category . "/video/" . $file . "/media.mp4"; //tobe uploaded


        header("Content-Type: " . mime_content_type($filepath));
        header('Content-Disposition: attachment; filename=' . $projectId . "_" . $file . ".mp4");

        while (ob_get_level()) {
            ob_end_clean();
        }

        if (readfile($filepath)) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Download started'));
        } else {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Download error'));
        }


        break;

    case 40:
        if (generateThumbs(filter_input(INPUT_GET, 'file', FILTER_SANITIZE_STRING)))
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Thumbs generation successful', "track" => 1));


        break;

    case 41:
        $result = $media->media_files_ALIES(filter_input(INPUT_GET, 'file', FILTER_SANITIZE_NUMBER_INT));


        $row = $result->fetch();
        $filename = $row['FileName'];
        $alias = $row['Alias'];

        if (isset($filename)) {
            $directory = "../../uploads/" . $filename;
            $newname = "../../uploads/" . $alias;

            if (rename($directory, $newname))
                echo json_encode(array("response" => true, "status" => 'success', "filename" => $filename, "alias" => $alias));
            else
                echo json_encode(array("response" => false, "status" => 'fail', "data" => 'no data'));
        } else
            echo json_encode(array("response" => false, "status" => 'fail', "data" => 'no data'));

        break;

    case 42:
        $lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_NUMBER_INT);
        $mediaId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $field = filter_input(INPUT_POST, 'field', FILTER_SANITIZE_STRING);
        $value = $_POST['content'];


        $fileName = 'caption' . $lang . '.vtt';
        if ($field === 'overlaying_text')
            $url = $ROOT_PATH . '/uploads/overlays/f_' . $mediaId . '/';
        else {
            $url = $ROOT_PATH . '/uploads/subs/f_' . $mediaId . '/';
        }

        $result = $media->subFileTextsLanguage_UPDATE($mediaId, $lang, $field, $value);

        if ($result) {
            $fileName_srt = $url . $fileName;

            if (!is_dir($url))
                mkdir($url);

            $handle = fopen($fileName_srt, 'w') or die('Cannot open file:  ' . $fileName_srt);

            if (fwrite($handle, $_POST['content']))
                echo json_encode(array("response" => true, "status" => 'success', "message" => 'Save successful'));
            else
                echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Save unsuccessful'));
        } else {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while saving data'));
        }

        break;
    case 43:

        break;

    case 44:
        $result = $media->NTSLanguages();
        $num = $result->rowCount();
        $media->setXMLHeader();
        $string = '<toolbar>'
            . '<item type="buttonSelect" id="language" text="New Language" img="fa fa-check-square"  openAll="true" renderSelect="true" mode="select">';
        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $string .= '<item type="button" id="' . $row['languages_id'] . '" text="' . $row['name'] . '" img="fa fa-check-square-o"/>';
            }
        }
        $string .= '</item><item type="separator" id="sep_6" />';
        $string .= '<item type="button" id="delete" text="Delete" img="fa fa-trash " /><item type="separator" id="sep_2" />';
        $string .= '</toolbar>';
        echo $string;
        break;

    case 45:
        $result = $media->subFileTextsLanguage_INSERT(
            filter_input(INPUT_POST, 'language', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'media', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Record updated'));
        } else {
            echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Error while updating'));
        }
        break;
    case 46:
        $result = $media->subFileTextsLanguage(filter_input(INPUT_GET, 'media', FILTER_SANITIZE_NUMBER_INT));
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $ID . "' >";
                echo "<cell><![CDATA[" . $name . "]]></cell>";
                echo "</row>";
            }
        } else {
            echo "<row id ='no_1' >";
            echo "<cell></cell>";
            echo "<cell>No language set</cell>";
            echo "</row>";
        }
        echo "</rows >";
        break;

    case 47:
        $result = $media->audioTextLanguage_INSERT(
            filter_input(INPUT_POST, 'language', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'media', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Record updated'));
        } else {
            echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Error while updating'));
        }
        break;
    case 48:
        $result = $media->audioTextLanguage_GET(filter_input(INPUT_GET, 'media', FILTER_SANITIZE_NUMBER_INT));

        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<row id = '" . $languages_id . "' >";
                echo "<cell><![CDATA[" . $ID . "]]></cell>";
                echo "<cell><![CDATA[" . $name . "]]></cell>";
                echo "</row>";
            }
        } else {
            echo "<row id ='no_' >";
            echo "<cell></cell>";
            echo "<cell>No language set</cell>";
            echo "</row>";
        }
        echo "</rows >";
        break;

    case 49:
        $result = $media->audioFiles_GET(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $status = ($Status == 1) ? 'True' : 'False';
                echo "<row id = '" . $ID . "' >";
                echo "<cell><![CDATA[" . $SortID . "]]></cell>";
                echo "<cell><![CDATA[" . $BeginTime . "]]></cell>";
                echo "<cell><![CDATA[" . $EndTime . "]]></cell>";
                echo "<cell><![CDATA[" . $FileAlias . "]]></cell>";
                echo "<cell><![CDATA[" . $Updated . "]]></cell>";
                echo "<cell><![CDATA[" . $status . "]]></cell>";
                echo "</row>";
            }
        }
        echo "</rows >";
        break;

    case 50:
        $begin = filter_input(INPUT_POST, 'endTime', FILTER_SANITIZE_STRING) ? filter_input(INPUT_POST, 'endTime', FILTER_SANITIZE_STRING) : '00:00:00';

        $result = $media->audioFileDetail_INSERT(
            filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), $begin);

        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Record inserted'));
        } else {
            echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Error while updating'));
        }
        break;

    case 51:
        $sort = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_NUMBER_INT);
        $mediaId = filter_input(INPUT_POST, 'media', FILTER_SANITIZE_NUMBER_INT);
        $itemId = filter_input(INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT);
        $lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_NUMBER_INT);

        $fileName = $sort . '.txt';

        $url = $ROOT_PATH . '/uploads/audiotext/f_' . $mediaId . '/';

        if (!is_dir($url))
            mkdir($url);

        $result = $media->audioFileDetail_UPDATE_TEXT($itemId, $fileName, strip_tags($_POST['content']));

        if ($result) {
            $fileurl = $url . '/' . $lang . '/';
            $fileName_text = $fileurl . $fileName;

            if (!is_dir($fileurl))
                mkdir($fileurl);

            $handle = fopen($fileName_text, 'w') or die('Cannot open file:  ' . $fileName_text);

            if (fwrite($handle, strip_tags($_POST['content'])))
                echo json_encode(array("response" => true, "status" => 'success', "message" => 'Save successful'));
            else
                echo json_encode(array("response" => true, "status" => 'fail', "message" => 'Save unsuccessful'));
        } else {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while saving data'));
        }

        break;
    case 52:
        $result = $media->audioFile_CONTENT(
            filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "content" => $result));
        } else {
            echo json_encode(array("response" => true, "status" => 'fail', "content" => ''));
        }

        break;

    case 53:
        $result = $media->audioFileDetail_UPDATE_FIELDS(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), filter_input(INPUT_POST, 'field', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING));

        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Save successful'));
        } else {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while saving data'));
        }
        break;

    case 54:
        $result = $media->audioFileDetail_DELETE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));

        if ($result) {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Save successful'));
        } else {
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while saving data'));
        }
        break;

    case 55:
        $result = $media->audioMovie(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                echo "<row id = '" . $ID . "' >";
                echo "<cell><![CDATA[" . $ItemName . "]]></cell>";
                echo "<cell><![CDATA[" . $LastUpdate . "]]></cell>";
                echo "</row>";
            }
        }
        echo "</rows >";
        break;

    case 56:
        $pathname = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);
        $lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_NUMBER_INT);
        $result = $media->audioFiles_TIMESTAMP(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));


        $num = $result->rowCount();

        $ITEM = array();

        if ($num > 0) {

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $ITEM[] = $row;
            }
        }


        $WEBVTTcontent = "WEBVTT" . "\r\n" . "\r\n"; //start webvtt subtitle text

        foreach ($ITEM as $KEY => $VAL) {

            //get time diffrence
            $WEBVTTcontent .= $VAL['BeginTime'] . ".000 --> " . $VAL['EndTime'] . ".000" . "\r\n";

            //actual spoken statement
            $WEBVTTcontent .= $VAL['Content'] . "\r\n" . "\r\n";
        }


        //save to this path
        $basepath = '../../uploads/subs/' . $pathname;


        if (!is_dir($basepath))
            mkdir($basepath);

        $filename = "caption" . $lang . ".vtt";


        if (file_put_contents($basepath . "/" . $filename, $WEBVTTcontent))
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Action Complete'));
        else
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while generating text'));


        break;

    case 57:
        $result = $media->audioMovieByVideoID(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));

        $media->setXMLHeader();
        echo "<rows >";
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                echo "<row id = '" . $languageID . "' >";
                echo "<cell><![CDATA[" . $ID . "]]></cell>";
                echo "<cell><![CDATA[" . $name . "]]></cell>";
                echo "<cell><![CDATA[" . $ItemName . "]]></cell>";
                echo "<cell><![CDATA[" . $LastUpdate . "]]></cell>";
                echo "</row>";
            }
        } else {
            echo "<row id = 'xxx' >";
            echo "<cell></cell>";
            echo "<cell></cell>";
            echo "<cell><![CDATA[No audio available]]></cell>";
            echo "</row>";
        }
        echo "</rows >";
        break;

    case 58:
        $result = $media->audioTextLanguage_DELETE(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));

        if ($result)
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Delete successful'));
        else
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while deleting item'));
        break;


    case 59:
        $result = $media->filmScript_SAVE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), $_POST['content']);
        if ($result)
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Update successful'));
        else
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while updating item'));
        break;

    case 60:
        $result = $media->filmScript_GET(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            $row = $result->fetch();

            echo json_encode(array("response" => true, "content" => $row['content']));
        }
        break;

    case 61:
        $result = $media->media_file_ALL();

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $update = $media->updateVideosWithHashCodes($ID);
                if ($update)
                    echo json_encode(array("response" => true, "status" => 'success', "message" => 'Update successful on' . $ID));
            }
        }


        if ($result)
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Update successful'));
        else
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while updating item'));
        break;

    case 62:
        $result = $media->comments_SAVE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), $_POST['content']);
        if ($result)
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Update successful'));
        else
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while updating item'));
        break;

    case 63:
        $result = $media->mediaInfo_SAVE(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT), $_POST['content']);
        if ($result)
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Update successful'));
        else
            echo json_encode(array("response" => true, "status" => 'success', "message" => 'Error while updating item'));
        break;

    case 64:
        $result = $media->mediaInfo_GET(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            $row = $result->fetch();
            echo json_encode(array("response" => true, "content" => $row['content'].' '));
            http_response_code(200);
        } else
            http_response_code(401);
        break;

    case 65:
        $result = $media->mediaComment_GET(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        if ($result) {
            $row = $result->fetch();
            echo json_encode(array("response" => true, "content" => $row['content'].' '));
            http_response_code(200);
        } else
            http_response_code(401);
        break;

}

function printXML(stdClass $obj, $x, $eid, $isRoot = false)
{

    $itemName = xml_entities($obj->name);
    $itemId = $obj->id;

    $projectId = generateProjectId($itemId);

    echo "<item id='" . $obj->id . "' text='" . $projectId . "| " . $itemName . "'>" . PHP_EOL;
    echo '<userdata name="thisurl">index.php?page=' . $obj->id . '</userdata>' . PHP_EOL;
    $y = 0;
    foreach ($obj->children as $child) {
        ++$y;
        printXML($child, $y, $eid);
    }

    echo '</item>';
}

function xml_entities($string)
{
    return strtr(
        $string, array(
            "<" => "&lt;",
            ">" => "&gt;",
            '"' => "&quot;",
            "'" => "&apos;",
            "&" => "&amp;",
        )
    );
}

function generateProjectId($itemId)
{
    if (strlen($itemId) == 1) {
        $projectId = "P00000" . $itemId . "";
    } else if (strlen($itemId) == 2) {
        $projectId = "P0000" . $itemId . "";
    } else if (strlen($itemId) == 3) {
        $projectId = "P000" . $itemId . "";
    } else if (strlen($itemId) == 4) {
        $projectId = "P00" . $itemId . "";
    } else if (strlen($itemId) == 5) {
        $projectId = "P0" . $itemId . "";
    } else {
        $projectId = $itemId;
    }

    return $projectId;
}

function generateThumbs($value)
{

    # code...
    $mediafile = "../../uploads/";
    $path_info = pathinfo($mediafile . $value);

    $type = $path_info['extension'];

    $name = $path_info['filename'];


    if ($type !== 'mp3') {
        $newdir = '../../uploads/thumbnails/' . $name;

        $pixelleddir = '../../uploads/pixels/' . $name;

//        Create directory if doesnt exist yet
        if (!is_dir($newdir))
            mkdir($newdir, 0777, true);

        if (!is_dir($pixelleddir))
            mkdir($pixelleddir, 0777, true);

        $destFile = $newdir . '/pic';
        $pixelledDestFile = $pixelleddir . '/thumbnail_main.jpg';

        if (is_dir($pixelleddir))
            createPixelledThumb($mediafile . $value, $pixelledDestFile);

        $file = createMovieThumb($mediafile . $value, $destFile);
        return $file;
    } else {
        echo json_encode(array("response" => true, "status" => 'fail', "track" => 2, "message" => 'Thumbs generation unsuccessful'));
    }
}

function createMovieThumb($srcFile, $destFile)
{
    // Change the path according to your server.
    $ffmpeg_path = 'C:\\ffmpeg\\bin\\';

    $output = array();

    $cmd = sprintf(
        '%sffmpeg -i %s -vf fps=1 -s 192x108  %s', $ffmpeg_path, $srcFile, $destFile . '%d.jpg'
    );


    if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN'))
        $cmd = str_replace('/', DIRECTORY_SEPARATOR, $cmd);
    else
        $cmd = str_replace('\\', DIRECTORY_SEPARATOR, $cmd);

    exec($cmd, $output, $retval);

    if ($retval)
        return false;

    return true;
}

function createPixelledThumb($srcFile, $destFile)
{
    // Change the path according to your server.
    $ffmpeg_path = 'C:\\ffmpeg\\bin\\';

    $output = array();

    $cmd = sprintf(
        '%sffmpeg -i %s -vframes 1 -ss 00:00:20.000 -s 1280x720  %s', $ffmpeg_path, $srcFile, $destFile
    );


    if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN'))
        $cmd = str_replace('/', DIRECTORY_SEPARATOR, $cmd);
    else
        $cmd = str_replace('\\', DIRECTORY_SEPARATOR, $cmd);

    exec($cmd, $output, $retval);

    if ($retval)
        return false;

    return true;
}

function getMediaType($type)
{
    $count = 0;
    $result = "";
    while ($count < strlen($type) && isValid($type[$count]))
        $result .= $type[$count++];

    return $result;

}

function isValid($string)
{
    return ctype_alnum($string);
}
