<?php

namespace session\auth;

use SessionDatabase;


class QueryExecutor
{
    private $conn;

    function __construct()
    {
        $this->conn = SessionDatabase::getInstance()::getConnection();
    }

    public function __destruct()
    {
        $this->conn = NULL;
    }


    public function query($traineeId, $password)
    {
        $query = /** @lang text */
            "
             SELECT
                    contact_attendent,
                    contact_id,
                    branch_id,
                    trainees.FirstName firstName,
                    trainees.LastName lastName,
				    email,
                    pass
            FROM
                    relation_contact
            JOIN trainees ON trainees.IntranetID = relation_contact.contact_id
            
            WHERE
               contact_id = ?

             AND pass = '" . md5($password) . "' ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $traineeId);
        $statement->execute();
        return $statement;

    }
}