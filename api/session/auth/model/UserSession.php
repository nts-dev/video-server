<?php


use session\auth\UserBO;
use session\auth\UserFlare;

class UserSession
{


    private UserBO $BOUser;
    private UserFlare $FlareUser;



    /**
     * @return
     */
    public function getBOUser():UserBO
    {
        return $this->BOUser;
    }

    /**
     * @param mixed $BOUser
     */
    public function setBOUser($BOUser)
    {
        $this->BOUser = $BOUser;
    }

    /**
     * @return
     */
    public function getFlareUser(): UserFlare
    {
        return $this->FlareUser;
    }

    /**
     * @param mixed $FlareUser
     */
    public function setFlareUser($FlareUser): void
    {
        $this->FlareUser = $FlareUser;
    }

}