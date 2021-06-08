<?php


namespace App\Controller;

use App\discogs_api\MyDiscogsApi;
use App\discogs_auth\DiscogsAuth;
use App\Form\SearchType;
use GuzzleHttp\Client;
use Jolita\DiscogsApi\DiscogsApi;
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
        $client = new Client();

        $discogsAuth = new DiscogsAuth();
        $token = $discogsAuth->getToken();
        $userAgent = $discogsAuth->getUserAgent();
        $username = $discogsAuth->getUserName();

        $discogs = new DiscogsApi($client, $token, $userAgent);
        $orders = $discogs->getMyOrders(1, 10, 'All');

        $myDiscogs = new MyDiscogsApi($client, $token, $userAgent);
        $drafted = $myDiscogs->getDraft($username);
        $violation = $myDiscogs->getViolation($username);

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
