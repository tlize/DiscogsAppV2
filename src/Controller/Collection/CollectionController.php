<?php


namespace App\Controller\Collection;


use App\Controller\Refactor\MainFunctionsController;
use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Pagination\MyPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collection", name="collection")
 */
class CollectionController extends AbstractController
{

    /**
     * @Route("/", name = "_list")
     */
    public function collection(MainFunctionsController $mc, DiscogsClient $dc, DiscogsAuth $auth, MyPaginator $mp): Response
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