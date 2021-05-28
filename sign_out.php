<?php
header("Content-Type: application/json; charset=UTF-8");

include('api/session/Commons.php');
include('includes.php');


doSignOut();

function doSignOut()
{
    $success = [
        'state' => true,
        'message' => 'successfully logged out'
    ];
    $fail = [
        'state' => false,
        'message' => 'Error signing out'
    ];
    echo session_destroy() ? json_encode($success) : json_encode($fail);
}



