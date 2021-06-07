<?php


namespace App\Controller\Admin;


use App\Entity\Country;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Jolita\DiscogsApi\DiscogsApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\discogs_auth\DiscogsAuth;

class AdminController extends AbstractController
{

    /**
     * test
     * @Route("/test", name = "test")
     */
    public function test(): Response
    {
        $client = new Client();

        $agent = new DiscogsAuth();
        $token = $agent->getToken();
        $userAgent = $agent->getUserAgent();

        $discogs = new DiscogsApi($client, $token, $userAgent);

        //$orders = $discogs->getMyOrders();

        $username = $agent->getUserName();
        //$inventory = $discogs->getMyInventory($username);

        $collection = $discogs
            ->get("users/$username/collection/folders/1/releases", '', [], true);

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