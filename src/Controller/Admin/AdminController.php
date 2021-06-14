<?php


namespace App\Controller\Admin;


use App\DiscogsApi\DiscogsClient;
use App\Entity\Country;
use App\Entity\ItemLabel;
use App\Entity\OrderCountry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * test
     * @Route("/test", name = "test")
     */
    public function test(): Response
    {
        dump($_POST);

        return $this->render("main/test.html.twig");
    }

    /**
     * set country from shipping address
     * use country and order_country tables
     * @Route("/setcountry/{orderId}", name = "set_country",
     *     methods={"GET"})
     */
    public function setBuyerCountry(EntityManagerInterface $em, $orderId): Response
    {
        //fetch all countries
        $countries = $em->getRepository(Country::class)->findAll();
        $discogsClient = new DiscogsClient();

        $orderCountry = new OrderCountry();
        $orderCountry->setOrderId($orderId);

        //get matching order
        $order = $discogsClient->getDiscogsClient()->orderWithId($orderId);

        foreach ($countries as $country) {
            //find which country name is included in shipping address
            if (strpos($order->getShippingAddress(), $country->getName()) != false) {
                $buyerCountry = $country->getName();
                $orderCountry->setCountry($buyerCountry);
            }
        }
        //populate order_country table
        $em->persist($orderCountry);
        $em->flush();
        $this->addFlash('success', 'Cool, country is now set for order ' . $orderId . ' !');
        return $this->render('order/detail.html.twig', ['order' => $order]);
    }

    /**
     * set item label from releaseId
     * use item_label table
     * @Route("/setlabel/{releaseId}", name = "set_label",
     *     methods={"GET"})
     */
    public function setItemLabel(EntityManagerInterface $em, $releaseId): Response
    {
        $discogsClient = new DiscogsClient();

        $itemLabel = new ItemLabel();
        $itemLabel->setReleaseId($releaseId);

        //get matching item
        $item = $discogsClient->getDiscogsClient()->release($releaseId);
        $itemLabel->setLabel($item->labels[0]->name);

        $em->persist($itemLabel);
        $em->flush();

        $this->addFlash('success', 'Cool, label is now set for item : ' . $item->title);
        dump($item, $itemLabel);

        return $this->render('main/test.html.twig');
    }

}