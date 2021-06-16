<?php


namespace App\Controller\Orders;

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
    public function list(int $page = 1): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }

        $discogsClient = new DiscogsClient();
        $orders = $discogsClient->getDiscogsClient()->getMyOrders($page);

        $myPaginator = new MyPaginator();
        $pagination = $myPaginator->paginate($orders, $page);

        return $this->render('order/list.html.twig', ['orders' => $orders, 'pagination' => $pagination]);
    }


    //details////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * order details
     * @Route("/{id}", name = "_detail",
     *     methods={"GET"})
     */
    public function detail($id): Response
    {
        $discogsClient = new DiscogsClient();
        $order = $discogsClient->getDiscogsClient()->orderWithId($id);

        if (empty($order)) {
            throw $this->createNotFoundException("Order not found !");
        }

        return $this->render('order/detail.html.twig', ['order' => $order]);
    }



}