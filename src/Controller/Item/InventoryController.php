<?php


namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InventoryController extends AbstractController
{

    /**
     * tous les items
     * @Route("/inventory", name = "inventory")
     */
    public function inventory()
    {
        return $this->render("item/inventory.html.twig");
    }
}