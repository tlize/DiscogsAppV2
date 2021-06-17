<?php


namespace App\Controller\Inventory;

use App\Controller\MainController;
use App\DiscogsApi\DiscogsClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item", name="item")
 */
class ItemController extends AbstractController
{

    //lists////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * all items
     * @Route("/", name = "_list")
     */
    public function itemList(): Response
    {
        $mc = new MainController();
        $page = $mc->getPage();
        $sort = $mc->getSort();
        $sortOrder = $mc->getSortOrder();

        $items = $mc->getSortedInventoryByStatus($page, 'All', $sort, $sortOrder);
        $pagination = $mc->getPagination($items, $page);

        return $this->render('item/list.html.twig', ['items' => $items, 'pagination' => $pagination]);
    }

    /**
     * all sold items
     * @Route("/sold", name = "_sold")
     */
    public function soldItems(): Response
    {
        $mc = new MainController();
        $page = $mc->getPage();
        $sort = $mc->getSort();
        $sortOrder = $mc->getSortOrder();

        $items = $mc->getSortedInventoryByStatus($page, 'Sold', $sort, $sortOrder);
        $pagination = $mc->getPagination($items, $page);

        return $this->render('item/sold.html.twig', ['items' => $items, 'pagination' => $pagination]);
    }

    /**
     * all items for sale
     * @Route("/forsale", name = "_for_sale")
     */
    public function itemsForSale(): Response
    {
        $mc = new MainController();
        $page = $mc->getPage();
        $sort = $mc->getSort();
        $sortOrder = $mc->getSortOrder();

        $items = $mc->getSortedInventoryByStatus($page, 'For Sale', $sort, $sortOrder);
        $pagination = $mc->getPagination($items, $page);

        return $this->render('item/forsale.html.twig', ['items' => $items, 'pagination' => $pagination]);
    }


    //details////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * order item details
     * @Route("/{id}", name = "_detail",
     * requirements={"id" : "\d+"},
     * methods={"GET"})
     */
    public function detail($id): Response
    {
        $discogsClient = new DiscogsClient();
        $item = $discogsClient->getMyDiscogsClient()->getInventoryItem($id);

        return $this->render("inc/orderitemdescription.html.twig", ["item" => $item]);
    }



}
