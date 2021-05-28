<?php

namespace auth;

interface AuthenticationService
{


    /**
     * @param $traineeId
     * @param $password
     * @return mixed
     *
     *
     * Login flow
     *
     * - User enters NTS password and employee id
     * - System queries from BO user table where password and employee
     * - System returns user database row
     * - System authenticates from flare api using returned row data
     * - System stores token in file system :: TODO {Not sure}
     * - System starts and stores session
     */

    function authenticateClient($traineeId, $password);

    function storeUserSession(\UserSession $auth);

    function storeErrorSession($error);

    function getSession();

    function logout();

}