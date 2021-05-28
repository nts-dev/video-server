<?php


namespace session\auth;
use session\auth\User;


class UserClientContext
{
    private UserClient $client;
    public function __construct(UserClient $client)
    {
        assert($client != null);
        $this->client = $client;
    }


    public function authenticate(){
       return $this->client->authenticate();
    }

}