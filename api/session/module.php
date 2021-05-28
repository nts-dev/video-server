<?php


use session\RequestType;

$action = $_GET['action'];
include('Commons.php');


$bootstrap = App::getInstance();
$service = new ContentService();


switch ($action) {

    case RequestType::MODULE_ALL:
        $resultArray = $service->findAll();
        if (is_array($resultArray))
            XML::moduleGrid($resultArray);
        break;

    case RequestType::MODULE_FIND:
        //find by id
        $resultArray = $service->findById(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        XML::form($resultArray);
        break;

    case RequestType::MODULE_DELETE:

        header("Content-Type: application/json; charset=UTF-8");
        $data = [
            'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
            'user_id' => filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT),
            'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
        ];
        $result = $service->deleteById($data['id'], $data);
        echo json_encode($result);
        break;

    case RequestType::MODULE_ADD:
        header("Content-Type: application/json; charset=UTF-8");
        $payload = [
            'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
            'subject_id' => filter_input(INPUT_POST, 'subject_id', FILTER_SANITIZE_NUMBER_INT)
        ];
        $result = $service->save($payload);
        echo json_encode($result);
        break;

    case RequestType::MODULE_EDIT:
        header("Content-Type: application/json; charset=UTF-8");
        $data = [
            'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
            'user_id' => filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT),
            'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
        ];

        $result = $service->edit($data['id'], $data);
        echo json_encode($result);
        break;

    case RequestType::MODULE_SUBJECT:

        $resultArray = $service->findBySubject(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        if (is_array($resultArray))
            XML::moduleGrid($resultArray);
        break;

    case RequestType::MODULE_COMBO:
        $resultArray = $service->findBySubject(
            filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)
        );
        if (is_array($resultArray))
            XML::combo($resultArray);
        break;
}



