<?php

namespace session\auth;
use \session\auth\User;

class Response
{
    private bool $state;
    private $message;
    private ?User $user;

    public function __construct($state, User $data = null, $message = null)
    {
        $this->user = $data;
        $this->message = $message;
        $this->state = $state;
    }

    /**
     * @return State
     */
    public function getState(): bool
    {
        return $this->state;
    }

    /**
     * @return
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return mixed|null
     */
    public function getMessage()
    {
        return $this->message;
    }

}

class State
{
    const SUCCESS = true;
    const FAIL = false;
}