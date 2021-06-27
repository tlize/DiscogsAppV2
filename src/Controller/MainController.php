<?php


namespace App\Controller;

use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Entity\Order;
use App\Pagination\MyPaginator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Doctrine\ORM\EntityManagerInterface;
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
    public function home(DiscogsClient $dc,EntityManagerInterface $em, DiscogsAuth $auth): Response
    {
        $username = $auth->getUserName();

        $orders = $dc->getDiscogsClient()->getMyOrders(1, 10, 'All');

        $dbOrders = [];
        foreach ($orders->orders as $order) {
            $orderNum = $order->id;
            $dbOrder = $em->getRepository(Order::class)->findOneByOrderId($orderNum);
            $dbOrders[$orderNum] = $dbOrder;
        }

        $drafted = $dc->getMyDiscogsClient()->getDraft($username);
        $violation = $dc->getMyDiscogsClient()->getViolation($username);

        return $this->render("main/home.html.twig",
            ["orders" => $orders, 'dbOrders' => $dbOrders, "drafted" => $drafted, "violation" => $violation]);
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


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function getOrdersMonths(): array
    {
        $period = CarbonPeriod::create(Carbon::create('2019', '09', '22'), Carbon::now());
        $months = [];
        foreach ($period as $date) {
            $yearMonth = substr($date, 0, 7);
            $year = explode('-', $yearMonth)[0];
            $month = explode('-', $yearMonth)[1];
            $current = Carbon::create($year, $month);
            $months[$yearMonth]['name'] = $yearMonth;
            $months[$yearMonth]['created_after'] = Carbon::create($year, $month, 1, 00, 00, 00)
                    ->format('Y-m-d') . 'T00:00:00Z';
            $months[$yearMonth]['created_before'] = $current->endOfMonth()
                    ->format('Y-m-d') . 'T23:59:59Z';
        }
        return $months;
    }

}
