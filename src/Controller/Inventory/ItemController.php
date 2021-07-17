<?php


namespace App\Controller\Inventory;

use App\Controller\Refactor\InventoryFunctionsController;
use App\Controller\Refactor\MainFunctionsController;
use App\DiscogsApi\DiscogsClient;
use App\Form\PriceUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function itemList(MainFunctionsController $mc): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('artist');
        $sortOrder = $mc->getSortOrder('asc');
        $sortLink = $mc->getSortLink();

        $items = $mc->getSortedInventoryByStatus($page, 'All', $sort, $sortOrder);
        $pagination = $mc->getPagination($items, $page);

        return $this->render('item/list.html.twig', ['items' => $items, 'sortLink' => $sortLink, 'pagination' => $pagination]);
    }

    /**
     * all sold items
     * @Route("/sold", name = "_sold")
     */
    public function soldItems(MainFunctionsController $mc): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('artist');
        $sortOrder = $mc->getSortOrder('asc');
        $sortLink = $mc->getSortLink();

        $items = $mc->getSortedInventoryByStatus($page, 'Sold', $sort, $sortOrder);
        $pagination = $mc->getPagination($items, $page);

        return $this->render('item/sold.html.twig', ['items' => $items, 'sortLink' => $sortLink, 'pagination' => $pagination]);
    }

    /**
     * all items for sale
     * @Route("/forsale", name = "_for_sale")
     */
    public function itemsForSale(MainFunctionsController $mc): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('artist');
        $sortOrder = $mc->getSortOrder('asc');
        $sortLink = $mc->getSortLink();


        $items = $mc->getSortedInventoryByStatus($page, 'For Sale', $sort, $sortOrder);
        $pagination = $mc->getPagination($items, $page);

        return $this->render('item/forsale.html.twig', ['items' => $items, 'sortLink' => $sortLink, 'pagination' => $pagination]);
    }


    //details////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * item details
     * @Route("/{id}", name = "_detail",
     * requirements={"id" : "\d+"},
     * methods={"GET"})
     */
    public function detail(InventoryFunctionsController $ic, DiscogsClient $dc, $id): Response
    {
        $item = $dc->getMyDiscogsClient()->getInventoryItem($id);
        $release = $dc->getDiscogsClient()->release($item->release->id);
        $priceSuggestion = $ic->getPriceSuggestion($dc, $item);

        return $this->render("item/detail.html.twig", ["item" => $item, 'release' => $release, 'priceSuggestion' => $priceSuggestion]);
    }

    //edit price////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * edit item price
     * @Route("/{id}/price", name = "_price",
     * requirements={"id" : "\d+"})
     */
    public function editPrice(InventoryFunctionsController $ic, Request $request,DiscogsClient $dc, $id): Response
    {
        $item = $dc->getMyDiscogsClient()->getInventoryItem($id);
        $release = $dc->getDiscogsClient()->release($item->release->id);
        $priceSuggestion = $ic->getPriceSuggestion($dc, $item);
        $priceForm = $this->createForm(PriceUpdateType::class);

        $priceForm->handleRequest($request);
        if ($priceForm->isSubmitted() && $priceForm->isValid()) {
            $newPrice = $priceForm->getData()['price'];

            //$dc->getMyDiscogsClient()->updatePrice($id, $item->release->id, $item->condition, $newPrice);
            //$listingId, $releaseId, $condition, $newPrice

            dump($newPrice);
            $this->addFlash('success', 'Ok, price updated !');
            return $this->render('item/detail.html.twig', ['id' => $item->id, 'item' => $item, 'release' => $release, 'priceSuggestion' => $priceSuggestion]);
        }
        return $this->render('item/price.html.twig', ['id' => $item->id, 'item' => $item, 'release' => $release,
            'priceForm' => $priceForm->createView(), 'priceSuggestion' => $priceSuggestion]);
    }



}
