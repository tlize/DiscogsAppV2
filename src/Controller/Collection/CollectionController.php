<?php


namespace App\Controller\Collection;


use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Pagination\MyPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{

    /**
     * @Route("/collection", name = "collection_list")
     */
    public function collection(int $page = 1): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        $discogsClient = new DiscogsClient();

        $discogsAuth = new DiscogsAuth();
        $collection = $discogsClient->getMyDiscogsClient()
            ->getMyCollection($discogsAuth->getUserName(), $page);
        $collectionValue = $discogsClient->getMyDiscogsClient()
            ->getCollectionValue($discogsAuth->getUserName());

        $myPaginator = new MyPaginator();
        $pagination = $myPaginator->paginate($collection, $page);

        return $this->render("collection/list.html.twig", ['collection' => $collection, 'pagination' => $pagination, 'collectionValue' => $collectionValue]);
    }
}