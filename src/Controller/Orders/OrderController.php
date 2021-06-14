<?php


namespace App\Controller\Orders;

use App\DiscogsApi\DiscogsClient;
use App\Entity\OrderCountry;
use App\Pagination\MyPaginator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    //list////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * all orders
     * @Route("/order", name = "order_list")
     */
    public function list(EntityManagerInterface $em, int $page = 1): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }

        $discogsClient = new DiscogsClient();
        $orders = $discogsClient->getDiscogsClient()->getMyOrders($page);

        $orderCountries = [];
        foreach ($orders->orders as $order) {
            $orderNum = $order->id;
            $orderCountry = $em->getRepository(OrderCountry::class)->findOneByOrderId($orderNum);
            $orderCountries[$orderNum] = $orderCountry;
        }

        $myPaginator = new MyPaginator();
        $pagination = $myPaginator->paginate($orders, $page);

        return $this->render('order/list.html.twig', ['orders' => $orders, 'orderCountries' => $orderCountries, 'pagination' => $pagination]);
    }


    //details////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * order details
     * @Route("/order/{id}", name = "order_detail",
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


    //ranks////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * best buying countries
     * @Route("/countries", name = "best_countries_list")
     */
    public function bestCountries(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(OrderCountry::class)->findBestCountries();

        $bestCountries = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('best/countries.html.twig', ['bestCountries' => $bestCountries]);
    }

}