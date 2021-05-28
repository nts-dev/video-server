<?php

namespace session\auth;
interface  UserClient
{
    function authenticate() : Response;
}