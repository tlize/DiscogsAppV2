<?php


namespace App\Controller;

use App\Entity\Country;
use App\Entity\Item;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class MainController extends AbstractController
{
    /**
     * homepage
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

//    /**
//     * for setting country from shipping address
//     * using Country table
//     * @Route("/setcountry", name = "setcountry")
//     */
//    public function setBuyerCountry(EntityManagerInterface $em)
//    {
//        $countryRepo = $this->getDoctrine()->getRepository(Country::class);
//        $countries = $countryRepo->findAll();
//
//        $orderRepo = $this->getDoctrine()->getRepository(Order::class);
//        $orders = $orderRepo->findAll();
//
//        foreach ($orders as $order) {
//
//            $address = $order->getShippingAddress();
//            foreach ($countries as $country) {
//                if (strpos($address, $country->getName()) != false) {
//                    $buyerCountry = $country->getName();
//                    $order->setCountry($buyerCountry);
//                }
//            }
//
//            $em->persist($order);
//            $em->flush();
//        }
//
//        return $this->render('main/test.html.twig', ["countries"=>$countries, "order"=>$order]);
//    }

}