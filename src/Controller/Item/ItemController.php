<?php


namespace App\Controller\Item;

use App\Entity\Item;
use App\Form\ItemType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{

    /**
     * tous les items
     * @Route("/item", name = "item_list")
     */
    public function list(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
//        $items = $itemRepo->findExpensiveSoldItems();
        $items = $itemRepo->findBy([], ["artist" => "ASC"]);
        return $this->render("item/list.html.twig", ["items" => $items]);
    }

    /**
     * détails d'un item
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
     * @Route("/item/add", name="item_add")
     * @param $request
     * @return Response
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {

        $item = new Item();
        $item->setStatus('For sale');
        $item->setListed(new DateTime());

        $itemForm = $this->createForm(ItemType::class, $item);

        $itemForm->handleRequest($request);
        if ($itemForm->isSubmitted()) {
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
     * tous les items vendus
     * @Route("/item/sold", name = "item_sold")
     */
    public function soldItems(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepo->findSoldItems();
        return $this->render("item/sold.html.twig", ["items" => $items]);
    }

    /**
     * tous les items en vente
     * @Route("/item/forsale", name = "item_for_sale")
     */
    public function itemsForSale(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepo->findItemsForSale();
        return $this->render("item/forsale.html.twig", ["items" => $items]);
    }
}