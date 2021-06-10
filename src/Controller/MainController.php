<?php


namespace App\Controller;

use App\discogs_api\DiscogsClient;
use App\discogs_auth\DiscogsAuth;
use App\Form\SearchType;
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
    public function home(): Response
    {
        $discogsClient = new DiscogsClient();

        $discogsAuth = new DiscogsAuth();
        $username = $discogsAuth->getUserName();

        $orders = $discogsClient->getDiscogsClient()->getMyOrders(1, 10, 'All');
        $drafted = $discogsClient->getMyDiscogsClient()->getDraft($username);
        $violation = $discogsClient->getMyDiscogsClient()->getViolation($username);

        return $this->render("main/home.html.twig",
            ["orders" => $orders, "drafted" => $drafted, "violation" => $violation]);
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
            $data = $searchForm->getData();

           return $this->redirectToRoute('test', ['data' => $data]);
        }

        return $this->render('inc/searchform.html.twig', [
            'searchForm' => $searchForm->createView()
        ]);
    }

}
