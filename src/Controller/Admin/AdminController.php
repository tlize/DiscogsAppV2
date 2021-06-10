<?php


namespace App\Controller\Admin;


use App\discogs_api\DiscogsClient;
use App\discogs_auth\DiscogsAuth;
use App\Entity\Country;
use App\Entity\Order;
use App\Entity\OrderCountry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * test
     * @Route("/test", name = "test")
     */
    public function test(): Response
    {
        $discogsClient = new DiscogsClient();

        $discogsAuth = new DiscogsAuth();
        $username = $discogsAuth->getUserName();
        $collection = $discogsClient->getMyDiscogsClient()->getMyCollection($username);
        dump($collection);

        return $this->render("main/test.html.twig");
    }

    /**
     * for setting $country from $shipping address
     * using Country table
     * @Route("/setcountry", name = "setcountry")
     */
    public function setBuyerCountry(EntityManagerInterface $em): Response
    {
        $countries = $em->getRepository(Country::class)->findAll();

        $discogsClient = new DiscogsClient();

//        $allOrders = [];

        $ordersUpTo100 = $discogsClient->getDiscogsClient()->getMyOrders(1, 100, 'All');
        $jOrdersUpTo100 = json_decode($ordersUpTo100, true, null, JSON_OBJECT_AS_ARRAY);
        foreach ($jOrdersUpTo100 as $order) {

//            $order = json_decode($jOrder);
            $orderId = $order['id'];
            $shippingAddress = $order['shipping_address'];
            $orderCountry = new OrderCountry();
            $orderCountry->setOrderId($orderId);
            $orderCountry->setShippingAddress($shippingAddress);
            $em->persist($orderCountry);
        }
        $em->flush();
//        $ordersUpTo200 = $discogsClient->getDiscogsClient()->getMyOrders(2, 100, 'All');
//        foreach ($ordersUpTo200 as $order) {
//            $allOrders[] = $order;
//        }
//        $ordersUpTo300 = $discogsClient->getDiscogsClient()->getMyOrders(3, 100, 'All');
//        foreach ($ordersUpTo300 as $order) {
//            $allOrders[] = $order;
//        }

//        foreach ($orders as $order) {
//            $address = $order->getShippingAddress();
//            foreach ($countries as $country) {
//                if (strpos($address, $country->getName()) != false) {
//                    $buyerCountry = $country->getName();
//                    $order->setCountry($buyerCountry);
//                }
//            }
//            $em->persist($order);
//        }
//        $em->flush();
//        $this->addFlash('success', 'cool, country is now set for all orders !');
        dump($ordersUpTo100, $countries);
        return $this->render('main/test.html.twig');
    }

    /**
     * for setting $nbItems from $orderItems
     * after table Order was added this new column
     * @Route("/setnbofitems", name = "setnbofitems")
     */
    public function setItemsNb(EntityManagerInterface $em): Response
    {
        $orders = $em->getRepository(Order::class)->findAll();
        foreach ($orders as $order) {
            $order->setNbItems($order->getOrderItems()->count());
            $em->persist($order);
        }
        $em->flush();
        $this->addFlash('success', 'cool, nb of items is now set for all orders !');
        return $this->render('main/test.html.twig');
    }
}