<?php


use session\RequestType;

$action = $_GET['action'];
include('Commons.php');

$bootstrap = App::getInstance();
$mediaService = new MediaService();

switch ($action) {

    default:
        break;


    case RequestType::MEDIA_ALL:

        $resultArray = $mediaService->findAll();
        XML::videoGrid($resultArray);

        break;

    case RequestType::MEDIA_FIND:
        //find by id
        break;

    case RequestType::MEDIA_DELETE:

        header("Content-Type: application/json; charset=UTF-8");
        $data = [
            'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
            'user_id' => filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT)
        ];

        $result = $mediaService->deleteById($data['id'], $data);

        echo json_encode($result);
        break;


    case RequestType::MEDIA_ADD :
        header("Content-Type: application/json; charset=UTF-8");
        $data = [
            'module_id' => filter_input(INPUT_GET, 'module_id', FILTER_SANITIZE_NUMBER_INT),
            'title' => filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING),
            'subject_id' => filter_input(INPUT_GET, 'subject_id', FILTER_SANITIZE_STRING),
            'description' => filter_input(INPUT_GET, 'description', FILTER_SANITIZE_STRING),
            'file' => $_FILES['file']
        ];
        echo json_encode($mediaService->save($data));

        break;


    case RequestType::UPLOAD :

        //upload

        break;

    case RequestType::MEDIA_EDIT:
        $data = array(
            'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
            'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING)
        );

        $resultArray = $mediaService->edit($data['id'], $data);

        break;

    case RequestType::MEDIA_CATEGORY:

        $resultArray = $mediaService->findByCategory(
            filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)
        );

//        var_dump($resultArray); exit();
        if (is_array($resultArray))
            XML::videoGrid($resultArray);

        break;

    case RequestType::MEDIA_ENCODE:

        header("Content-Type: application/json; charset=UTF-8");

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $result = $mediaService->encodeMedia($id);
        echo json_encode($result);
        break;
}



