<?php


namespace App\Controller\Item;

use App\discogs_api\DiscogsClient;
use App\Entity\Item;
use App\Form\ItemType;
use App\Form\PriceUpdateType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{

    //lists////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function pgnLimit(QueryBuilder $query, int $limit, PaginatorInterface $paginator, Request $request): PaginationInterface
    {
        return $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );
    }

    /**
     * all items
     * @Route("/item", name = "item_list")
     */
    public function itemList(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Item::class)->findAllItems();
        $items = $this->pgnLimit($query, 100, $paginator, $request);

        return $this->render('item/list.html.twig', ['items' => $items]);
    }

    /**
     * all sold items
     * @Route("/item/sold", name = "item_sold")
     */
    public function soldItems(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Item::class)->findSoldItems();
        $items = $this->pgnLimit($query, 50, $paginator, $request);

        return $this->render("item/sold.html.twig", ["items" => $items]);
    }

    /**
     * all items for sale
     * @Route("/item/forsale", name = "item_for_sale")
     */
    public function itemsForSale(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Item::class)->findItemsForSale();
        $items = $this->pgnLimit($query, 50, $paginator, $request);
        return $this->render("item/forsale.html.twig", ["items" => $items]);
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

        return $this->render("item/detail.html.twig", ["item" => $item]);
    }


    //ranks////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * best selling artists
     * @Route("/artists", name = "best_artists_list")
     */
    public function bestArtists(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Item::class)->findBestArtists();

        $bestArtists = $paginator->paginate(
            $query,
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
        $query = $em->getRepository(Item::class)->findBestLabels();

        $bestLabels = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('best/labels.html.twig', ['bestLabels' => $bestLabels]);
    }


    //new////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * new item form
     * @Route("/item/add", name="item_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $item = new Item();
        $item->setStatus('For sale');
        $item->setListed(new DateTime());

        $itemForm = $this->createForm(ItemType::class, $item);

        $itemForm->handleRequest($request);
        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'One more item !');
            return $this->redirectToRoute('item_detail', ['id' => $item->getId()]);
        }

        return $this->render('item/add.html.twig', [
            'itemForm' => $itemForm->createView()
        ]);
    }

    /**
     * items to pick for new order
     * @Route("/item/neworder", name = "items_new_order")
     */
    public function itemsInOrder(EntityManagerInterface $em): Response
    {
        $items = $em->getRepository(Item::class)->findItemsForNewOrder();
        return $this->render("item/neworder.html.twig", ["items" => $items]);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * update item price
     * @Route("/item/{id}/price", name = "item_price",
     * requirements={"id" : "\d+"})
     * methods={"POST"})
     */
    public function updatePrice(EntityManagerInterface $em, Request $request, $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        $priceForm = $this->createForm(PriceUpdateType::class);

        $priceForm->handleRequest($request);
        if ($priceForm->isSubmitted() && $priceForm->isValid()) {
            $newPrice = $priceForm->getData();
            $item->setPrice($newPrice['price']);

            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'Ok, price updated !');
            return $this->render('item/detail.html.twig', ['id' => $item->getId(), 'item' => $item]);
        }
        return $this->render('item/price.html.twig', ['id' => $item->getId(), 'item' => $item,
            'priceForm' => $priceForm->createView()]);
    }
}
