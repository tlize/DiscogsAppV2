<?php


namespace App\Controller\Detail;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends AbstractController
{
    /**
     * détails d'un band/artist
     * @Route("artist/{artist}", name = "artist_detail",
     * requirements={"artist" : ".*"},
     * methods={"GET"})
     */
    public function artistDetail($artist): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepo->findArtistDetail($artist);
        return $this->render("detail/artist.html.twig", ["items" => $items]);
    }

    /**
     * détails d'un label
     * @Route("label/{label}", name = "label_detail",
     * requirements={"label" : ".*"},
     * methods={"GET"})
     */
    public function labelDetail($label): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepo->findLabelDetail($label);
        return $this->render("detail/label.html.twig", ["items" => $items]);
    }
}

