<?php


namespace App\Controller\Order;

use App\Entity\Order;
//use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    /**
     * toutes les commandes
     * @Route("/order", name = "order_list")
     */
    public function list(): Response
    {
        $orderRepo = $this->getDoctrine()->getRepository(Order::class);
        $orders = $orderRepo->findBy([], ["orderDate" => "DESC"]);
        return $this->render("order/list.html.twig", ["orders" => $orders]);
    }


}