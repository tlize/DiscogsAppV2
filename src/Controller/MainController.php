<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class MainController extends AbstractController
{
    /**
     * page d'accueil
     * @Route("/", name = "home")
     */
    public function home()
    {
        return $this->render("main/home.html.twig");
    }

    /**
     * page de test
     * @Route("/test", name = "test")
     */
    public function test()
    {
        return $this->render("main/test.html.twig");
    }

}