<?php

namespace session\auth;


use  \session\auth;


class UserDaoImp implements UserDao, UserData
{

    private QueryExecutor $queryExecutor;

    public function __construct()
    {
        $this->queryExecutor = new QueryExecutor();
    }

    public function getUser($traineeId, $password)
    {
        $result = $this->queryExecutor->query($traineeId, $password);



        if ($result->rowCount() < 1)
            return null;

        return $result->fetch();

    }
}