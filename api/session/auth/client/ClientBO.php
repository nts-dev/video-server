<?php

namespace session\auth;


use PHPUnit\Exception;



class ClientBO implements UserClient
{

    private string $password;
    private string $traineeId;
    private UserDao $dao;
    private User $user;

    public function __construct($traineeId, $password)
    {
        assert($traineeId != null);
        assert($password != null);

        $this->password = $password;
        $this->traineeId = $traineeId;
        $this->dao = new UserDaoImp();
        $this->user = new UserBO();
    }

    function authenticate() : Response
    {
        try {
            $row =  $this->dao->getUser($this->traineeId, $this->password);

            if ($row == null)
                return new Response(State::FAIL, $this->user);

            $this->user->setEmail($row['email']);
            $this->user->setFirstName($row['firstName']);
            $this->user->setLastName($row['lastName']);
            $this->user->setPassword($this->password);
            $this->user->setTraineeId($row['contact_id']);
            $this->user->setAttendent($row['contact_attendent']);

            return new Response(State::SUCCESS, $this->user);

        } catch (Exception $e) {
            return new Response(State::FAIL, $this->user, $e->getMessage());
        }
    }
}