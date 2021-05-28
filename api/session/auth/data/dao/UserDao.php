<?php

namespace session\auth;

interface UserDao
{

    function getUser($traineeId, $password);

}