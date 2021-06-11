<?php


namespace App\Controller;

use App\discogs_api\DiscogsClient;
use App\discogs_auth\DiscogsAuth;
use App\Entity\OrderCountry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * homepage
     * @Route("/", name = "home")
     */
    public function home(EntityManagerInterface $em): Response
    {
        $discogsClient = new DiscogsClient();
        $discogsAuth = new DiscogsAuth();
        $username = $discogsAuth->getUserName();

        $orderCountries = [];
        $orders = $discogsClient->getDiscogsClient()->getMyOrders(1, 10, 'All');

        foreach ($orders->orders as $order) {
            $orderNum = $order->id;
            $orderCountry = $em->getRepository(OrderCountry::class)->findOneByOrderId($orderNum);
            $orderCountries[$orderNum] = $orderCountry;
        }
        $drafted = $discogsClient->getMyDiscogsClient()->getDraft($username);
        $violation = $discogsClient->getMyDiscogsClient()->getViolation($username);

        return $this->render("main/home.html.twig",
            ["orders" => $orders, "orderCountries" => $orderCountries, "drafted" => $drafted, "violation" => $violation]);
    }


}
