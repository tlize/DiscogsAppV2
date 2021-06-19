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
    public function itemList(MainController $mc): Response
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
    public function soldItems(MainController $mc): Response
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
    public function itemsForSale(MainController $mc): Response
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
     * order item details
     * @Route("/{id}", name = "_detail",
     * requirements={"id" : "\d+"},
     * methods={"GET"})
     */
    public function detail(DiscogsClient $dc, $id): Response
    {
        $item = $dc->getMyDiscogsClient()->getInventoryItem($id);
        $release = $dc->getDiscogsClient()->release($item->release->id);
        $priceSuggestion = $this->getPriceSuggestion($dc, $item);

        return $this->render("item/detail.html.twig", ["item" => $item, 'release' => $release, 'priceSuggestion' => $priceSuggestion]);
    }


    public function getPriceSuggestion(DiscogsClient $dc, $item) {
        $suggestedPrices = [];
        $suggestions = $dc->getMyDiscogsClient()->getPriceSuggestion($item->release->id);
        foreach ($suggestions as $suggestion) {
            $suggestedPrices[] = $suggestion;
        }
        $condition = $item->condition;
        $suggested = 0;
        switch ($condition) :
            case 'Mint (M)' : $suggested = $suggestedPrices[0]->value; break;
            case 'Near Mint (NM or M-)' : $suggested = $suggestedPrices[1]->value; break;
            case 'Very Good Plus (VG+)' : $suggested = $suggestedPrices[2]->value; break;
            case 'Very Good (VG)' : $suggested = $suggestedPrices[3]->value; break;
            case 'Good Plus (G+)' : $suggested = $suggestedPrices[4]->value; break;
            case 'Good (G)' : $suggested = $suggestedPrices[5]->value; break;
            case 'Fair (F)' : $suggested = $suggestedPrices[6]->value; break;
            case 'Poor (P)' : $suggested = $suggestedPrices[7]->value; break;
        endswitch;

        return $suggested;
    }


}
