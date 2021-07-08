<?php


namespace App\Controller\Orders;

use App\Controller\MainController;
use App\DiscogsApi\DiscogsClient;
use App\Entity\Country;
use App\Entity\Order;
use App\Pagination\MyPaginator;
use Doctrine\ORM\EntityManagerInterface;
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
    public function list(MainController $mc, DiscogsClient $dc, EntityManagerInterface $em, MyPaginator $paginator): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('id');
        $sortOrder = $mc->getSortOrder('desc');
        $sortLink = $mc->getSortLink();

        $orders = $dc->getDiscogsClient()->getMyOrders($page, 50, 'All', $sort, $sortOrder);
        $pagination = $paginator->paginate($orders, $page);

        $dbOrders = [];
        foreach ($orders->orders as $order) {
            $orderNum = $order->id;
            $dbOrder = $em->getRepository(Order::class)->findOneByOrderId($orderNum);
            $dbOrders[$orderNum] = $dbOrder;
        }

        return $this->render('order/list.html.twig', ['orders' => $orders, 'dbOrders' => $dbOrders,
            'sortLink' => $sortLink, 'pagination' => $pagination]);
    }

    /**
     * orders by month
     * @Route("/months", name = "_months")
     */
    public function Months(EntityManagerInterface $em): Response
    {
        //$months = $mc->getOrdersMonths();
        $orderMonths = $em->getRepository(Order::class)->getMonthList();

        dump($orderMonths);
        return $this->render('order/months.html.twig', ['months' => $orderMonths]);
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * set country from shipping address
     * using Country table
     * @Route("/{id}/dbcreate", name = "_db_create")
     */
    public function createDbOrder(DiscogsClient $dc, EntityManagerInterface $em, $id): Response
    {
        $countries = $em->getRepository(Country::class)->findAll();

        $order = $dc->getDiscogsClient()->orderWithId($id);
        $address = $order->shipping_address;

        $dbOrder = new Order();

        foreach ($countries as $country) {
            if (strpos($address, $country->getName()) != false) {
                $buyerCountry = $country->getName();
                $dbOrder->setCountry($buyerCountry);
            }
        }
        $dbOrder->setOrderNum($id);
        $dbOrder->setMonth(substr($order->created, 0, 7));
        $em->persist($dbOrder);
        $em->flush();
        $this->addFlash('success', 'Cool, order ' . $id . ' is now in database with country 
            (' . $dbOrder->getCountry() . ') and month (' . $dbOrder->getMonth() . ')');
        dump($dbOrder);
        return $this->render('order/detail.html.twig', ['order' => $order]);
    }

}