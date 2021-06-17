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

    public function getPageAndSort(): array
    {
        $pgSt = [];
        $page = 1;
        $sort = 'artist';
        $sortOrder = 'asc';
        $sortLink = 'asc';

        if (isset($_GET['page'])) $page = $_GET['page'];
        if (isset($_GET['sort'])) $sort = $_GET['sort'];
        if (isset($_GET['sort_order'])) $sortOrder = $_GET['sort_order'];
        if (strpos($_SERVER['QUERY_STRING'], '&sort_order=asc')) $sortLink = 'desc';

        $pgSt['page'] = $page;
        $pgSt['sort'] = $sort;
        $pgSt['sortOrder'] = $sortOrder;
        $pgSt['sortLink'] = $sortLink;

        return $pgSt;
    }

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
        return $this->getPageAndSort()['page'];
    }

    public function getSort()
    {
        return $this->getPageAndSort()['sort'];
    }

    public function getSortOrder()
    {
        return $this->getPageAndSort()['sortOrder'];
    }

    public function getSortLink()
    {
        return $this->getPageAndSort()['sortLink'];
    }

}
