<?php


namespace App\Controller\Collection;


use App\Controller\MainController;
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
    public function collection(): Response
    {
        $mc = new MainController();
        $page = $mc->getPage();
        $sort = $mc->getSort('artist');
        $sortOrder = $mc->getSortOrder('asc');
        $sortLink = $mc->getSortLink();

        $discogsClient = new DiscogsClient();
        $discogsAuth = new DiscogsAuth();

        $collection = $discogsClient->getMyDiscogsClient()
            ->getMyCollection($discogsAuth->getUserName(), $page, $sort, $sortOrder);
        $collectionValue = $discogsClient->getMyDiscogsClient()
            ->getCollectionValue($discogsAuth->getUserName());

        $myPaginator = new MyPaginator();
        $pagination = $myPaginator->paginate($collection, $page);

        return $this->render("collection/list.html.twig", ['collection' => $collection, 'pagination' => $pagination,
            'sortLink' => $sortLink, 'collectionValue' => $collectionValue]);
    }
}