<?php


namespace App\discogs_api;


use Jolita\DiscogsApi\DiscogsApi;

class MyDiscogsApi extends DiscogsApi
{

    public function getMyCollection(string $username)
    {
        return $this->get("users/$username/collection/folders/1/releases", '', [], true);
    }

    public function getDraft(string $userName)
    {
        return $this->get("users/$userName/inventory", '', ['status' => 'draft'], true);
    }

    public function getViolation(string $userName)
    {
        return $this->get("users/$userName/inventory", '', ['status' => 'violation'], true);
    }

    public function getInventoryItem(string $id)
    {
        return $this->get("marketplace/listings/$id", '', [], true);
    }
}