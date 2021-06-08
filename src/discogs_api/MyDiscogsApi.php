<?php


namespace App\discogs_api;


use Jolita\DiscogsApi\DiscogsApi;

class MyDiscogsApi extends DiscogsApi
{

    public function getMyCollection(string $username)
    {
        return $this->get("users/$username/collection/folders/1/releases", '', [], true);
//        return $this->get("users/{$userName}/inventory", '', [], true);
    }
}