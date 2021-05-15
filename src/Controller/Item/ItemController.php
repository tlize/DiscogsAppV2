<?php


namespace App\Controller\Item;

use App\Entity\Item;
use App\Form\ItemType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{

    /**
     * item details
     * @Route("/item/{id}", name = "item_detail",
     *     requirements={"id" : "\d+"},
     *     methods={"GET"})
     */
    public function detail($id): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $item = $itemRepo->find($id);

        return $this->render("item/detail.html.twig", ["item" => $item]);
    }

    /**
     * add an item
     * @Route("/item/add", name="item_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $item = new Item();
        $item->setStatus('For sale');
        $item->setListed(new DateTime());

        $itemForm = $this->createForm(ItemType::class, $item);

        $itemForm->handleRequest($request);
        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'One more item !');
            return  $this->redirectToRoute('item_detail', ['id'=>$item->getId()]);
        }

        return $this->render('item/add.html.twig', [
            'itemForm'=> $itemForm->createView()
        ]);
    }

    /**
     * all sold items
     * @Route("/item/sold", name = "item_sold")
     */
    public function soldItems(PaginatorInterface $paginator,Request $request): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $query = $itemRepo->paginateSoldItems();

        $items = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            100
        );
        return $this->render("item/sold.html.twig", ["items" => $items]);
    }

    /**
     * all items for sale
     * @Route("/item/forsale", name = "item_for_sale")
     */
    public function itemsForSale(PaginatorInterface $paginator,Request $request): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $query = $itemRepo->paginateItemsForSale();

        $items = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            100
        );
        return $this->render("item/forsale.html.twig", ["items" => $items]);
    }

    /**
     * all items
     * @Route("/item", name = "item_list")
     */
    public function itemList(PaginatorInterface $paginator,Request $request): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $query = $itemRepo->paginateItems();

        $items = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            100
        );
        return $this->render('item/list.html.twig', ['items'=>$items]);
    }

    /**
     * best selling artists
     * @Route("/artists", name = "best_artists_list")
     */
    public function bestArtists(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $bestArtists = $itemRepo->findBestArtists();

        return $this->render('best/artists.html.twig', ['bestArtists'=>$bestArtists]);
    }

    /**
     * best selling labels
     * @Route("/labels", name = "best_labels_list")
     */
    public function bestLabels(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $bestLabels = $itemRepo->findBestLabels();

        return $this->render('best/labels.html.twig', ['bestLabels'=>$bestLabels]);
    }

    /**
     * items to pick for new order
     * @Route("/item/neworder", name = "items_new_order")
     */
    public function itemsInOrder(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepo->findItemsForNewOrder();

        return $this->render("item/neworder.html.twig", ["items" => $items]);
    }
}