<?php


namespace App\Controller\Inventory;

use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Pagination\MyPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{

    //lists////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * all items
     * @Route("/item", name = "item_list")
     */
    public function itemList(int $page = 1, string $sort = 'artist', string $sortOrder = 'asc'): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        switch ($sort) :
            case 'price' : $sortOrder = 'desc'; break;
            case 'item' : case 'artist' : case 'label' : case 'catno' : $sortOrder = 'asc'; break;
        endswitch;
        $items = $this->getSortedInventoryByStatus($page, 'All', $sort, $sortOrder);
        $pagination = $this->getPagination($items, $page);

        return $this->render('item/list.html.twig', ['items' => $items, 'pagination' => $pagination]);
    }

    /**
     * all sold items
     * @Route("/item/sold", name = "item_sold")
     */
    public function soldItems(int $page = 1, string $sort = 'artist', string $sortOrder = 'asc'): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        switch ($sort) :
            case 'price' : $sortOrder = 'desc'; break;
            case 'item' : case 'artist' : case 'label' : case 'catno' : $sortOrder = 'asc'; break;
        endswitch;
        $items = $this->getSortedInventoryByStatus($page, 'Sold', $sort, $sortOrder);
        $pagination = $this->getPagination($items, $page);

        return $this->render('item/sold.html.twig', ['items' => $items, 'pagination' => $pagination]);
    }

    /**
     * all items for sale
     * @Route("/item/forsale", name = "item_for_sale")
     */
    public function itemsForSale(int $page = 1, string $sort = 'artist', string $sortOrder = 'asc'): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }
        switch ($sort) :
            case 'price' : $sortOrder = 'desc'; break;
            case 'item' : case 'artist' : case 'label' : case 'catno' : $sortOrder = 'asc'; break;
        endswitch;
        $items = $this->getSortedInventoryByStatus($page, 'For Sale', $sort, $sortOrder);
        $pagination = $this->getPagination($items, $page);

        return $this->render('item/forsale.html.twig', ['items' => $items, 'pagination' => $pagination]);
    }


    //details////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * item details
     * @Route("/item/{id}", name = "item_detail",
     * requirements={"id" : "\d+"},
     * methods={"GET"})
     */
    public function detail($id): Response
    {
        $discogsClient = new DiscogsClient();
        $item = $discogsClient->getMyDiscogsClient()->getInventoryItem($id);

        return $this->render("inc/itemdescription.html.twig", ["item" => $item]);
    }


    //refactoring////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function getSortedInventoryByStatus($page, $status, $sort, $sortOrder)
    {
        $discogsAuth = new DiscogsAuth();
        $username = $discogsAuth->getUserName();

        $discogsClient = new DiscogsClient();
        return $discogsClient->getMyDiscogsClient()->getInventoryItems(
            $username, $page, 50, $status, $sort, $sortOrder);
    }

    public function getPagination($items, $page): array
    {
        $myPaginator = new MyPaginator();
        return $myPaginator->paginate($items, $page);
    }

}
