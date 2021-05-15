<?php


namespace App\Controller\Order;

use App\Entity\Country;
use App\Entity\Item;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\OrderType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    /**
     * all orders
     * @Route("/order", name = "order_list")
     */
    public function list(PaginatorInterface $paginator,Request $request): Response
    {
        $orderRepo = $this->getDoctrine()->getRepository(Order::class);
        $query = $orderRepo->paginateAllWithDetails();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            500
        );
        return $this->render('order/list.html.twig', ['orders' => $orders]);
    }

    /**
     * order details
     * @Route("/order/{id}", name = "order_detail",
     *     requirements={"id" : "\d+"},
     *     methods={"GET"})
     */
    public function detail($id): Response
    {
        $orderRepo = $this->getDoctrine()->getRepository(Order::class);
        $order = $orderRepo->find($id);

        if (empty($order)) {
            throw $this->createNotFoundException("Order not found !");
        }

        return $this->render('order/detail.html.twig', ['order'=>$order]);
    }

    /**
     * best buying countries
     * @Route("/countries", name = "best_countries_list")
     */
    public function bestCountries(): Response
    {
        $itemRepo = $this->getDoctrine()->getRepository(Order::class);
        $bestCountries = $itemRepo->findBestCountries();

        return $this->render('best/countries.html.twig', ['bestCountries'=>$bestCountries]);
    }

    /**
     * new order
     * @Route("/order/new", name="order_new")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {

        $orderItems = new ArrayCollection();
        $total = 0;

        if(isset($_POST['id']))
        {
            foreach ($_POST['id'] as $id)
            {
                $item = $em->getRepository(Item::class)->find($id);

                $description = $item->getArtist() . " - " . $item->getTitle() . " (" . $item->getFormat() . ")";

                $orderItem = new OrderItem();

                $orderItem->setStatus('');
                $orderItem->setOrderTotal(0);
                $orderItem->setItemId($item->getListingId());
                $orderItem->setItemPrice($item->getPrice());
                $orderItem->setItemFee(0);
                $orderItem->setDescription($description);
                $orderItem->setReleaseId($item->getReleaseId());
                $orderItem->setMediaCondition($item->getMediaCondition());
                $orderItems->add($orderItem);

                $total += $item->getPrice();
            }
        }



        $order = new Order();
        $order->setOrderDate(new DateTime());
        $order->setOrderItems($orderItems);
        $order->setTotal($total);


        $orderForm = $this->createForm(OrderType::class, $order);

        $orderForm->handleRequest($request);
        if ($orderForm->isSubmitted() && $orderForm->isValid())
        {
//            $em->persist($order);

            $countryRepo = $this->getDoctrine()->getRepository(Country::class);
            $countries = $countryRepo->findAll();

            $address = $order->getShippingAddress();
            foreach ($countries as $country)
            {
                if (strpos($address, $country->getName()) != false)
                {
                    $buyerCountry = $country->getName();
                    $order->setCountry($buyerCountry);
                }
            }

            $em->persist($order);
            $em->flush();








//            $em->flush();

            $this->addFlash('success', 'One more order !');
            return  $this->redirectToRoute('order_detail', ['id'=>$order->getId()]);
        }

        return $this->render('order/new.html.twig', [
            'orderForm'=> $orderForm->createView(),
            'orderItems'=>$orderItems,
            'total'=>$total
        ]);
    }
}