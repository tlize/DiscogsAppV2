<?php


namespace App\Controller;

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


}

//todo : tri par nb items, total, avg, dans best labels et best artists
// tri par nb orders, total, avg, dans best countries
// avec lien knp_pagination_sortable() en sous-titre ?
//blabla