<?php


use session\RequestType;

$action = $_GET['action'];

include('Commons.php');


$bootstrap = App::getInstance();


$service = new CommentService();
header("Content-Type: application/json; charset=UTF-8");

switch ($action) {


    case RequestType::COMMENT_FIND:
        //find by id
        $result = $service->findById(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
        echo json_encode($result);
        break;

    case RequestType::COMMENT_ADD:
        $data = [
            'upload_id' => filter_input(INPUT_POST, 'upload_id', FILTER_SANITIZE_NUMBER_INT),
            'content' => $_POST['text'],
        ];

        $result = $service->save($data);
        echo json_encode($result);
        break;

}



