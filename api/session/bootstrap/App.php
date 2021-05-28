<?php


use auth\AuthenticationServiceImpl;
use session\Network;

class App
{
    private static $INSTANCE = null;


    private function __construct()
    {

    }

    static public function getInstance(): App
    {
        if (self::$INSTANCE == null)
            self::$INSTANCE = new App();
        return self::$INSTANCE;
    }


    public static function startSessionIfNotAvailable($trainee, $identifier): UserSession
    {
        $authenticationService = new AuthenticationServiceImpl();
        $session = $authenticationService->getSession();
        if ($session == null) {
            $authenticationService->authenticateClient($trainee, $identifier);
        }
        return $authenticationService->getSession();
    }

}