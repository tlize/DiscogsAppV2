<?php


namespace App\Controller\Order;

use App\Entity\Order;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    /**
     * all orders
     * @Route("/order", name = "order_list")
     */
    public function list(PaginatorInterface $paginator,Request $request): Response
    {
        $orderRepo = $this->getDoctrine()->getRepository(Order::class);
        $query = $orderRepo->paginateAllWithDetails();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('order/list.html.twig', ['orders' => $orders]);
    }

    /**
     * order details
     * @Route("/order/{id}", name = "order_detail",
     *     requirements={"id" : "\d+"},
     *     methods={"GET"})
     */
    public function detail($id): Response
    {
        $orderRepo = $this->getDoctrine()->getRepository(Order::class);
        $order = $orderRepo->find($id);

        if (empty($order)) {
            throw $this->createNotFoundException("Order not found !");
        }

        return $this->render('order/detail.html.twig', ['order'=>$order]);
    }

    /**
     * best buying countries
     * @Route("/countries", name = "best_countries_list")
     */
    public function bestCountries(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Order::class);
        $bestCountries = $itemRepo->findBestCountries();

        return $this->render('best/countries.html.twig', ['bestCountries'=>$bestCountries]);
    }
}