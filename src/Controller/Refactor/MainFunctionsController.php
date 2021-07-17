<?php


namespace App\Controller\Refactor;


use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Pagination\MyPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainFunctionsController extends AbstractController
{

    //lists pagination and sorting/////////////////////////////////////////////////////////////////////////////////////

    public function getSortedInventoryByStatus($page, $status, $sort, $sortOrder)
    {
        $discogsAuth = new DiscogsAuth();
        $username = $discogsAuth->getUserName();

        $discogsClient = new DiscogsClient();
        return $discogsClient->getMyDiscogsClient()->getInventoryItems(
            $username, $page, 50, $status, $sort, $sortOrder);
    }

    public function getPagination($items, $page): array
    {
        $myPaginator = new MyPaginator();
        return $myPaginator->paginate($items, $page);
    }

    public function getPage()
    {
        $page = 1;
        if (isset($_GET['page'])) $page = $_GET['page'];
        return $page;
    }

    public function getSort($sort)
    {
        if (isset($_GET['sort'])) $sort = $_GET['sort'];
        return $sort;
    }

    public function getSortOrder($sortOrder)
    {
        if (isset($_GET['sort_order'])) $sortOrder = $_GET['sort_order'];
        return $sortOrder;
    }

    public function getSortLink(): string
    {
        $sortLink = 'asc';
        if (strpos($_SERVER['QUERY_STRING'], '&sort_order=asc')) $sortLink = 'desc';
        return $sortLink;
    }



}