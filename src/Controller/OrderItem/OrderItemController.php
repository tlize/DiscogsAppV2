<?php


namespace App\Controller\OrderItem;


use App\Entity\OrderItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderItemController extends AbstractController
{


    /**
     * order item details
     * @Route("/orderitem/{id}", name = "order_item_detail",
     *     requirements={"id" : "\d+"},
     *     methods={"GET"})
     */
    public function detail($id, Request $request)
    {
        $orderItemRepo = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderitem = $orderItemRepo->find($id);

        if (empty($orderitem)) {
            throw $this->createNotFoundException("Item not found !");
        }

        dump($orderitem);

        return $this->render('orderitem/detail.html.twig', ['orderitem'=>$orderitem]);
    }
}