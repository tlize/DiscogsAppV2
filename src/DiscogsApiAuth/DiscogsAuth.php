<?php


namespace App\DiscogsApiAuth;


class DiscogsAuth
{
    private $token = 'VOkYDEKmIbqJjCATHMTxmBPHTnZDyUKZXQIJZmOS';
    private $userAgent = 'discogsInventory';
    private $userName = 'tlize';

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }


}