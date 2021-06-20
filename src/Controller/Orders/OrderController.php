<?php


namespace App\Controller\Orders;

use App\Controller\MainController;
use App\DiscogsApi\DiscogsClient;
use App\Pagination\MyPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order", name = "order")
 */
class OrderController extends AbstractController
{
    //list////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * all orders
     * @Route("/", name = "_list")
     */
    public function list(MainController $mc, DiscogsClient $dc, MyPaginator $paginator): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('id');
        $sortOrder = $mc->getSortOrder('desc');
        $sortLink = $mc->getSortLink();

        $orders = $dc->getDiscogsClient()->getMyOrders($page, 50, 'All', $sort, $sortOrder);

        $pagination = $paginator->paginate($orders, $page);

        return $this->render('order/list.html.twig', ['orders' => $orders, 'sortLink' => $sortLink, 'pagination' => $pagination]);
    }

    /**
     * orders by month
     * @Route("/months", name = "_months")
     */
    public function Months(MainController $mc
//        , MyPaginator $paginator
    ): Response
    {
//        $page = $mc->getPage();
        $months = $mc->getOrdersMonths();
//        $pagination = $paginator->paginate($months, $page);

        dump($months);
        return $this->render('order/months.html.twig', ['months' => $months
//            , 'pagination' => $pagination
        ]);
    }

    /**
     * orders for one month
     * @Route("/month/{monthName}", name = "_month")
     */
    public function MonthOrders($monthName, DiscogsClient $dc, MainController $mc): Response
    {
        $months = $mc->getOrdersMonths();
        $month = $months[$monthName];
        $orders = $dc->getMyDiscogsClient()->getOrdersByMonth($month['created_after'], $month['created_before']);

        dump($monthName, $orders);
        return $this->render('order/month_detail.html.twig', ['name' => $monthName, 'orders' => $orders]);
    }


    //details////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * order details
     * @Route("/{id}", name = "_detail",
     *     methods={"GET"})
     */
    public function detail(DiscogsClient $dc,$id): Response
    {
        $order = $dc->getDiscogsClient()->orderWithId($id);

        if (empty($order)) {
            throw $this->createNotFoundException("Order not found !");
        }

        return $this->render('order/detail.html.twig', ['order' => $order]);
    }



}