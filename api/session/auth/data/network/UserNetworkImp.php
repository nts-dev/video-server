<?php

namespace session\auth;

use PHPUnit\Util\Exception;
use session\config\Constants;

class UserNetworkImp implements UserNetwork, UserData
{

    public function getUser(UserBO $user)
    {
        assert($user != null);

        $ch = curl_init();


        $data = array(
            "is_offline" => 1,
            "internal" => 1,
            "display_name" => $user->getFirstName(),
            "first_name" => $user->getFirstName(),
            "last_name" => $user->getLastName(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword());

        $POST = json_encode($data);

        $URL = Constants::API_URL .self::ENDPOINT;

        $HEADER = [
            'Content-Type: application/json'
        ];


        curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $HEADER);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        return $server_output;

    }
}