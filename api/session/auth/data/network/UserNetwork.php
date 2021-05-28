<?php

namespace session\auth;
interface UserNetwork
{
    const ENDPOINT = "login";

    function getUser(UserBO $user);
}