<?php


namespace App\Controller;

use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * homepage
     * @Route("/", name = "home")
     */
    public function home(DiscogsClient $dc,EntityManagerInterface $em, DiscogsAuth $auth): Response
    {
        $username = $auth->getUserName();

        $orders = $dc->getDiscogsClient()->getMyOrders(1, 10, 'All');
        $orderNums = [];

        foreach ($orders->orders as $order) {
            $orderNum = $order->id;
            $orderNums[] = $orderNum;
        }

        $dbOrders = $em->getRepository(Order::class)->getOrderList($orderNums);
        $drafted = $dc->getMyDiscogsClient()->getDraft($username);
        $violation = $dc->getMyDiscogsClient()->getViolation($username);

        return $this->render("main/home.html.twig",
            ["orders" => $orders, 'dbOrders' => $dbOrders, "drafted" => $drafted, "violation" => $violation]);
    }

    /**
     * test
     * @Route("/test", name = "test")
     */
    public function test(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        dump($_SERVER);
        return $this->render("main/test.html.twig");
    }



}
