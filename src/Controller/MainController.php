<?php


namespace App\Controller;

use App\Controller\Refactor\OrderFunctionsController;
use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
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
    public function home(DiscogsClient $dc,OrderFunctionsController $oc,EntityManagerInterface $em, DiscogsAuth $auth): Response
    {
        $username = $auth->getUserName();

        $orders = $dc->getDiscogsClient()->getMyOrders(1, 10, 'All');
        $dbOrders = $oc->getOrdersCountries($em, $orders);
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
