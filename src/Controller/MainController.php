<?php


namespace App\Controller;

use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Pagination\MyPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * homepage
     * @Route("/", name = "home")
     */
    public function home(): Response
    {
        $discogsClient = new DiscogsClient();
        $discogsAuth = new DiscogsAuth();
        $username = $discogsAuth->getUserName();

        $orders = $discogsClient->getDiscogsClient()->getMyOrders(1, 10, 'All');

        $drafted = $discogsClient->getMyDiscogsClient()->getDraft($username);
        $violation = $discogsClient->getMyDiscogsClient()->getViolation($username);

        return $this->render("main/home.html.twig",
            ["orders" => $orders, "drafted" => $drafted, "violation" => $violation]);
    }

    /**
     * test
     * @Route("/test", name = "test")
     */
    public function test(): Response
    {
        dump($_SERVER);
        return $this->render("main/test.html.twig");
    }


    //refactoring////////////////////////////////////////////////////////////////////////////////////////////////////////

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
