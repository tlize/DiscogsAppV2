<?php


namespace App\Controller\Inventory;

use App\DiscogsApi\DiscogsClient;
use App\DiscogsApiAuth\DiscogsAuth;
use App\Entity\ItemLabel;
use App\Entity\OrderStatsObject;
use App\Pagination\MyPaginator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{

    //lists////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * all items
     * @Route("/item", name = "item_list")
     */
    public function itemList(EntityManagerInterface $em, int $page = 1): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        $items = $this->getInventoryByStatus($page, 'All');
        $itemLabels = $this->getItemsLabels($em, $items);
        $pagination = $this->getPagination($items, $page);

        return $this->render('item/list.html.twig', ['items' => $items, 'itemLabels' => $itemLabels, 'pagination' => $pagination]);
    }

    /**
     * all sold items
     * @Route("/item/sold", name = "item_sold")
     */
    public function soldItems(EntityManagerInterface $em, int $page = 1): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        $items = $this->getInventoryByStatus($page, 'Sold');
        $itemLabels = $this->getItemsLabels($em, $items);
        $pagination = $this->getPagination($items, $page);

        return $this->render('item/sold.html.twig', ['items' => $items, 'itemLabels' => $itemLabels, 'pagination' => $pagination]);
    }

    /**
     * all items for sale
     * @Route("/item/forsale", name = "item_for_sale")
     */
    public function itemsForSale(EntityManagerInterface $em, int $page = 1): Response
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        $items = $this->getInventoryByStatus($page, 'For Sale');
        $itemLabels = $this->getItemsLabels($em, $items);
        $pagination = $this->getPagination($items, $page);

        return $this->render('item/forsale.html.twig', ['items' => $items, 'itemLabels' => $itemLabels, 'pagination' => $pagination]);
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


    //ranks////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * best selling artists
     * @Route("/artists", name = "best_artists_list")
     */
    public function bestArtists(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $bestArtists = $paginator->paginate(
            $em->getRepository(OrderStatsObject::class)->findBestArtists(),
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('best/artists.html.twig', ['bestArtists' => $bestArtists]);
    }

    /**
     * best selling labels
     * @Route("/labels", name = "best_labels_list")
     */
    public function bestLabels(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $bestLabels = $paginator->paginate(
            $em->getRepository(OrderStatsObject::class)->findBestLabels(),
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('best/labels.html.twig', ['bestLabels' => $bestLabels]);
    }


    //refactoring////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function getInventoryByStatus($page, $status)
    {
        $discogsAuth = new DiscogsAuth();
        $username = $discogsAuth->getUserName();

        $discogsClient = new DiscogsClient();
        return $discogsClient->getMyDiscogsClient()->getInventoryItems($username, $page, 50, $status);
    }

    public function getItemsLabels($em, $items): array
    {
        $itemLabels = [];
        foreach ($items->listings as $item) {
            $releaseId = $item->release->id;
            $itemLabel = $em->getRepository(ItemLabel::class)->findOneByReleaseId($releaseId);
            $itemLabels[$releaseId] = $itemLabel;
        }
        return $itemLabels;
    }

    public function getPagination($items, $page): array
    {
        $myPaginator = new MyPaginator();
        return $myPaginator->paginate($items, $page);
    }
}
