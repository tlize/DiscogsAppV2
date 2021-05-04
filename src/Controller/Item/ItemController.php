<?php


namespace App\Controller\Item;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{

    /**
     * tous les items
     * @Route("/item", name = "item_list")
     */
    public function list()
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepo->findBy([], ["artist" => "ASC"]);


        return $this->render("item/list.html.twig", ["items" => $items]);
    }

    /**
     * détails d'un item
     * @Route("/item/{id}", name = "item_detail",
     *     requirements={"id" : "\d+"},
     *     methods={"GET"})
     */
    public function detail($id, Request $request)
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $item = $itemRepo->find($id);


        return $this->render("item/detail.html.twig", ["item" => $item]);
    }

//    /**
//     * @Route("/disque/{id}", name="disque_detail",
//     *     requirements={"id" : "\d+"},
//     *     methods={"GET"})
//     */
//    public function detail($id, Request $request)
//    {
//        $disqueRepo = $this->getDoctrine()->getRepository(Disque::class);
//        $disque = $disqueRepo->find($id);
//
//        if (empty($disque)){
//            throw $this->createNotFoundException("Ce disque n'existe pas !");
//        }
//
//        return $this->render('disque/detail.html.twig', [
//            "disque"=>$disque
//        ]);
//    }
}