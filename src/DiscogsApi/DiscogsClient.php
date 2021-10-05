<?php


namespace App\DiscogsApi;


use App\DiscogsApiAuth\DiscogsAuth;
use GuzzleHttp\Client;
use Jolita\DiscogsApi\DiscogsApi;

class DiscogsClient
{

    public function getDiscogsClient(): DiscogsApi
    {
        $client = new Client();

        $discogsAuth = new DiscogsAuth();
        $token = $discogsAuth->getToken();
        $userAgent = $discogsAuth->getUserAgent();

        return new DiscogsApi($client, $token, $userAgent);
    }

    public function getMyDiscogsClient(): MyDiscogsApi
    {
        $client = new Client();

        $discogsAuth = new DiscogsAuth();
        $token = $discogsAuth->getToken();
        $userAgent = $discogsAuth->getUserAgent();

        return new MyDiscogsApi($client, $token, $userAgent);
    }
}