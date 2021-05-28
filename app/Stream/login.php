<?php

$action = $_GET['action'];

include_once 'config/database.php';

class HandleAuth {

    function __construct($db) {
        $this->conn = $db;
    }

    function __destruct() {
        $this->conn = NULL;
    }

    public function returnStatement($statement) {
        if ($statement->execute()) {
            return $statement;
        } else {
            return false;
        }
    }

    function login($userid, $password) {
        $query = "
             SELECT
                    contact_attendent,
                    contact_id,
                    branch_id,
            pass
            FROM
                    nts_site.relation_contact
            JOIN nts_site.trainees ON trainees.IntranetID = relation_contact.contact_id
            WHERE

                            contact_id =?

             AND pass = ? ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $userid);
        $statement->bindParam(2, md5($password));
        return $this->returnStatement($statement);
    }

}

$database = new AppDatabase();

$db = $database->getConnection();

$auth = new HandleAuth($db);


switch ($action) {
    case 1:
        session_start();
        unset($_SESSION['user_session']);
        if (session_destroy()) {
            header("Location: /Training/index.php");
        }
        echo $_SESSION['user_session'] ;
        break;
    default:
//        $result = $auth->login(9656,
//                '1moche'
//                );
        $result = $auth->login(filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING),
                filter_input(INPUT_POST, 'graDuration', FILTER_SANITIZE_STRING)
                );
        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            echo "success";
            $_SESSION['user_session'] = $row['contact_id'];
        } else {
            echo 'Contact ID or password does not exist';
        }
        break;
}



