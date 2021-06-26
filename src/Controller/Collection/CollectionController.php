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
    public function collection(MainController $mc, DiscogsClient $dc, DiscogsAuth $auth, MyPaginator $mp): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('artist');
        $sortOrder = $mc->getSortOrder('asc');
        $sortLink = $mc->getSortLink();

        $collection = $dc->getMyDiscogsClient()->getMyCollection($auth->getUserName(), $page, $sort, $sortOrder);
        $collectionValue = $dc->getMyDiscogsClient()->getCollectionValue($auth->getUserName());

        $pagination = $mp->paginate($collection, $page);

        return $this->render("collection/list.html.twig", ['collection' => $collection, 'pagination' => $pagination,
            'sortLink' => $sortLink, 'collectionValue' => $collectionValue]);
    }
}