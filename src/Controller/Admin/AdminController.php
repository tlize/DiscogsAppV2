<?php


namespace App\Controller\Admin;


use App\discogs_api\MyDiscogsApi;
use App\discogs_auth\DiscogsAuth;
use App\Entity\Country;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Jolita\DiscogsApi\DiscogsApi;
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
        $client = new Client();

        $discogsAuth = new DiscogsAuth();
        $token = $discogsAuth->getToken();
        $userAgent = $discogsAuth->getUserAgent();
        $username = $discogsAuth->getUserName();

        $discogs = new DiscogsApi($client, $token, $userAgent);
        $inventory = $discogs->getMyInventory($username);
        $orders = $discogs->getMyOrders();

        $myDiscogs = new MyDiscogsApi($client, $token, $userAgent);
        $collection = $myDiscogs->getMyCollection($username);

        dump($inventory, $orders, $collection);

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

        $orders = $em->getRepository(Order::class)->findAll();

        foreach ($orders as $order) {
            $address = $order->getShippingAddress();
            foreach ($countries as $country) {
                if (strpos($address, $country->getName()) != false) {
                    $buyerCountry = $country->getName();
                    $order->setCountry($buyerCountry);
                }
            }
            $em->persist($order);
        }
        $em->flush();
        $this->addFlash('success', 'cool, country is now set for all orders !');
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