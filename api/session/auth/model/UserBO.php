<?php


namespace session\auth;

class UserBO implements User
{
    private $password;
    private $email;
    private $traineeId;
    private $firstName;
    private $lastName;
    private $attendent;

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTraineeId()
    {
        return $this->traineeId;
    }

    /**
     * @param mixed $traineeId
     */
    public function setTraineeId($traineeId)
    {
        $this->traineeId = $traineeId;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getAttendent()
    {
        return $this->attendent;
    }

    /**
     * @param mixed $attendent
     */
    public function setAttendent($attendent)
    {
        $this->attendent = $attendent;
    }


}