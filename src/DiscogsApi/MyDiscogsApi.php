<?php


namespace App\DiscogsApi;


use Carbon\Carbon;
use Jolita\DiscogsApi\DiscogsApi;
use Jolita\DiscogsApi\Exceptions\DiscogsApiException;
use Psr\Http\Message\ResponseInterface;

class MyDiscogsApi extends DiscogsApi
{

    public function getMyCollection(string $username, int $page, string $sort, string $sortOrder)
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

    public function getPriceSuggestion(string $id)
    {
        return $this->get("marketplace/price_suggestions/$id", '', [], true);
    }

    public function getOrdersByMonth($createdAfter, $createdBefore, string $sort = null, string $sortOrder = null) {
        $query = [
            'page' => $page ?? 1,
            'per_page' => $perPage ?? 50,
            'created_after' => $createdAfter ?? Carbon::create('2019', '09', '22')->toIso8601ZuluString(),
            'created_before' => $createdBefore ?? Carbon::now()->toIso8601ZuluString(),
            'sort' => $sort ?? 'id',
            'sort_order' => $sortOrder ?? 'asc',
        ];
        return $this->getAuthenticated("marketplace/orders", '', $query);
    }

    /**
     * @throws DiscogsApiException
     */
    public function updatePrice($listingId, $releaseId, $condition, $newPrice): ResponseInterface
    {
        $resource = "marketplace/listings/$listingId";
        return $this->client->post(
            $this->url($this->path($resource)),
            ['query' => [
                'release_id' => $releaseId,
                'condition' => $condition,
                'price' => $newPrice,
                'status' => 'For Sale',
                'token' => $this->token(),
                ],
            ]
        );
    }


}