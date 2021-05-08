<?php


namespace App\Controller;

use App\Entity\Item;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class MainController extends AbstractController
{
    /**
     * page d'accueil
     * @Route("/", name = "home")
     */
    public function home(): Response
    {
        $orderRepo = $this->getDoctrine()->getRepository(Order::class);
        $orders = $orderRepo->getLatestOrders();
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepo->findExpensiveSoldItems();
        $drafted = $itemRepo->findOutOfShop();
        return $this->render("main/home.html.twig", ["orders" => $orders, "items" => $items, "drafted" => $drafted]);
    }

}