<?php

namespace auth;


use session\auth\ClientBO;
use session\auth\ClientFlare;
use session\auth\Response;
use session\auth\State;
use session\auth\UserBO;
use session\auth\UserClientContext;
use UserSession;


class AuthenticationServiceImpl implements AuthenticationService
{


    public function __construct()
    {
        if(isset($_SESSION) && isset($_SESSION['ERRORS']))
            unset($_SESSION['ERRORS']);
    }

    function authenticateClient($traineeId, $password): bool
    {

        assert($traineeId != null);
        assert($password != null);

        /**
         *
         * 1 Authenticate user from BO
         */
        $BOResponse = $this->authenticateFromBO($traineeId, $password);


//        var_dump($BOAuthenticatedUser);

        if ($BOResponse->getState() == State::FAIL) {
             $this->storeErrorSession($BOResponse->getMessage());
            return false;
        }


        /**
         *
         * 2 Request token from flare api
         */
        $FlareResponse = $this->authenticateFromFlare($BOResponse->getUser());




        if ($FlareResponse->getState() == State::FAIL) {
            $this->storeErrorSession($FlareResponse->getMessage());
            return false;
        }


        /**
         *
         * 3 Store user session
         */

        if(isset($_SESSION['ERRORS']))
            return false;


        $userSession = new UserSession();
        $userSession->setFlareUser($FlareResponse->getUser());
        $userSession->setBOUser($BOResponse->getUser());
        $this->storeUserSession($userSession);


        return true;
    }

    /**
     *
     *
     *
     *
     *
     * @param $traineeId
     * @param $password
     * @return Response
     *
     *
     *
     */
    private function authenticateFromBO($traineeId, $password): Response
    {

        $client = new UserClientContext(new ClientBO($traineeId, $password));
        return $client->authenticate();
    }


    /**
     *
     *
     *
     *
     *
     * @param UserBO $user type of UserBO
     * @return Response type of UserFlare
     *
     *
     *
     */
    private function authenticateFromFlare(UserBO $user): Response
    {

        assert($user != null);

        $client = new UserClientContext(new ClientFlare($user));
        return $client->authenticate();
    }


    /**
     *
     *
     *
     *
     *
     *
     * @param UserSession $auth
     *
     *
     *
     *
     */

    function storeUserSession(UserSession $auth)
    {
        assert($auth != null);
        $_SESSION["USER"] = serialize($auth);
        session_commit();

    }

    function storeErrorSession($error)
    {
        $_SESSION["ERRORS"] = serialize($error);
        session_commit();

    }

    function getSession(): ?UserSession
    {

        if ($this->sessionExists()) {
            $session = $_SESSION["USER"];
            return unserialize($session);
        }
        return null;
    }


    private function sessionExists(): bool
    {
        return !empty($_SESSION) && isset($_SESSION['USER']);
    }

    function logout(): bool
    {
        return session_destroy();
    }
}