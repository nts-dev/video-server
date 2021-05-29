<?php

namespace session\auth;



class UserFlare implements User
{

    private String $token;
    private String $tokenType;
    private String $expireAt;

    /**
     * @return String
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param String $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return String
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @param String $tokenType
     */
    public function setTokenType(string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return String
     */
    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    /**
     * @param String $expireAt
     */
    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

}