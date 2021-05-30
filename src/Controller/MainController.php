<?php


namespace App\Controller;

use App\Entity\Item;
use App\Entity\Order;
use App\Form\SearchType;
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
        $query = $em->getRepository(Order::class)->findAllWithDetails();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );
        $drafted = $em->getRepository(Item::class)->findOutOfShop();

        return $this->render("main/home.html.twig", ["orders" => $orders, "drafted" => $drafted]);
    }


    /**
     * search form
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $searchForm = $this->createForm(SearchType::class);

        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            //todo : traitement du formulaire
            // redirection selon les choix

//            if (isset($_POST['name']) && isset($_POST['quest']) && isset($_POST['color'])) {
//                $name = $_POST['name'];
//                $quest = $_POST['quest'];
//                $color = $_POST['color'];
//                switch ($color) :
//                    case 'sold' :
//                        return $this->redirectToRoute('item_sold', ['name' => $name, 'quest' => $quest]);
//                    case 'forsale' :
//                        return $this->redirectToRoute('item_for_sale', ['name' => $name, 'quest' => $quest]);
//                    case 'all' :
//                        return $this->redirectToRoute('item_list', ['name' => $name, 'quest' => $quest]);
//                endswitch;
//            }

            $data = $searchForm->getData();

//            $this->addFlash('success', 'form has been filled');
//            dump($_POST);
           return $this->redirectToRoute('test', ['data' => $data]);
        }



        $uri = ($_SERVER['REQUEST_URI']);
        $offset = strlen($_SERVER['BASE']) + 1;
        $clic = substr($uri, $offset);
        dump($_SERVER);
        return $this->render('inc/searchform.html.twig', [
            'searchForm' => $searchForm->createView()
        ]);
    }

}
