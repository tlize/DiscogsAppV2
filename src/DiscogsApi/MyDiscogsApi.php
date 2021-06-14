<?php


namespace App\DiscogsApi;


use Jolita\DiscogsApi\DiscogsApi;

class MyDiscogsApi extends DiscogsApi
{

    public function getMyCollection(string $username, int $page)
    {
        $query = [
            'page' => $page ?? 1,
            'per_page' => $perPage ?? 50,
            'status' => $status ?? 'All',
            'sort' => $sort ?? 'artist',
            'sort_order' => $sortOrder ?? 'asc',
        ];
        return $this->get("users/$username/collection/folders/1/releases", '', $query, true);
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

    public function getInventoryItems(string $userName, int $page = null, int $perPage = null, string $status = null,
          string $sort = null, string $sortOrder = null)
    {
        $query = [
            'page' => $page ?? 1,
            'per_page' => $perPage ?? 50,
            'status' => $status ?? 'All',
            'sort' => $sort ?? 'artist',
            'sort_order' => $sortOrder ?? 'asc',
        ];

        return $this->getAuthenticated("users/$userName/inventory", '', $query);
    }

    public function getCollectionValue(string $username)
    {
        return $this->get("/users/$username/collection/value", '', [], true);
    }

}