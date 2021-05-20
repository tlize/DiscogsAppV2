<?php


namespace App\Controller;

use App\Entity\Country;
use App\Entity\Item;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * homepage
     * @Route("/", name = "home")
     */
    public function home(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Order::class)->paginateAllWithDetails();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );
        $drafted = $em->getRepository(Item::class)->findOutOfShop();

        return $this->render("main/home.html.twig", ["orders" => $orders, "drafted" => $drafted]);
    }

    /**
     * test
     * @Route("/test", name = "test")
     */
    public function test(): Response
    {
        dump($_POST);

        return $this->render("main/test.html.twig");
    }

    /**
     * for setting country from shipping address
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
            $em->flush();
        }
        $this->addFlash('success', 'cool, all countries are set !');
        return $this->render('main/test.html.twig');
    }

}