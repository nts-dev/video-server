<?php


use session\RequestType;

$action = $_GET['action'];
include('../../../auth.php');
include('Commons.php');


$bootstrap = App::getInstance();


$service = new MediaInfoService();

switch ($action) {
    case RequestType::TIMELINE_FIND:
        //find by id
        $result = $service->findById(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        echo json_encode($result);
        break;

    case RequestType::TIMELINE_EDIT:

        $data = [
            'id' => filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT),
            'upload_id' => filter_input(INPUT_POST, 'upload_id', FILTER_SANITIZE_NUMBER_INT),
            'text' => $_POST['text'],
        ];

        $result = $service->edit($data['id'], $data);
        echo json_encode($result);
        break;

    case RequestType::TIMELINE_ADD:
        $data = [
            'upload_id' => filter_input(INPUT_POST, 'upload_id', FILTER_SANITIZE_NUMBER_INT),
            'content' => $_POST['text'],
        ];

        $result = $service->save($data);
        echo json_encode($result);
        break;

}



