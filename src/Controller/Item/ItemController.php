<?php


namespace App\Controller\Item;

use App\Entity\Item;
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
     */
    public function add(EntityManagerInterface $em): Response
    {

        //TODO formulaire coming soon
        $item = new Item();
        $item->setListingId(123);
        $item->setArtist('great band');
        $item->setTitle('great title');
        $item->setLabel('great label');
        $item->setCatno('XXX666');
        $item->setFormat('LP');
        $item->setReleaseId(666);
        $item->setStatus('For Sale');
        $item->setPrice(20);
        $item->setListed(new DateTime());
        $item->setMediaCondition('VG+');

        $em->persist($item);
        $em->flush();
        $item->setPrice(40);

        $em->persist($item);
        $em->flush();

        $em->remove($item);
        $em->flush();

        dump($item);

        return $this->render('item/add.html.twig');
    }

    /**
     * tous les items vendus
     * @Route("/item/sold", name = "item_sold")
     */
    public function soldItems(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
//        $items = $itemRepo->findExpensiveSoldItems();
        $items = $itemRepo->findSoldItems();
        return $this->render("item/sold.html.twig", ["items" => $items]);
    }

}