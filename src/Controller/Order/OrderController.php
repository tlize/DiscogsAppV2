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
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {

//        $total = 0;

        $order = new Order();
        $order->setOrderDate(new DateTime());
        $orderForm = $this->createForm(OrderType::class, $order);
//        $orderItems = new ArrayCollection();
//        $em->persist($order);


//
//
//        $em->persist($order);
//
//
        $orderForm->handleRequest($request);
        if ($orderForm->isSubmitted() && $orderForm->isValid())
        {
            $itemRepo = $this->getDoctrine()->getRepository(Item::class);
            $items = $itemRepo->findItemsForNewOrder();


            dump($order, $items);
            return  $this->render('item/neworder.html.twig', [
                'order'=>$order, 'items'=>$items
            ]);
//            $this->redirectToRoute('order_detail', ['id'=>$order->getId()]);
        }


        return $this->render('order/form.html.twig', [
            'orderForm'=> $orderForm->createView()
        ]);

    }

    /**
     * confirm order
     * data treatment before updating db
     * @Route("/order/confirm", name="order_confirm")
     */
    public function confirmOrder(EntityManagerInterface $em): RedirectResponse
    {
        // récupération des items, calcul du total
        if(isset($_POST['id']) && isset($_POST['buyer']) && isset($_POST['orderNum']) && isset($_POST['shippingAddress']))
        {
            $buyer = $_POST['buyer'];
            $orderNum = $_POST['orderNum'];
            $shippingAddress = $_POST['shippingAddress'];

            $total = 0;
            $orderItems = new ArrayCollection();

            foreach ($_POST['id'] as $id)
            {
                $item = $em->getRepository(Item::class)->find($id);
                $item->setStatus('Sold');
                $em->persist($item);
                $em->flush();

                $description = $item->getArtist() . " - " . $item->getTitle() . " (" . $item->getFormat() . ")";

                $orderItem = new OrderItem();

                $orderItem->setOrderNum($orderNum);
                $orderItem->setBuyer($buyer);
                $orderItem->setShippingAddress($shippingAddress);
                $orderItem->setOrderDate(new DateTime());
                $orderItem->setOrderTotal(0);
                $orderItem->setStatus('');
                $orderItem->setItemId($item->getListingId());
                $orderItem->setItemPrice($item->getPrice());
                $orderItem->setItemFee(0);
                $orderItem->setDescription($description);
                $orderItem->setReleaseId($item->getReleaseId());
                $orderItem->setMediaCondition($item->getMediaCondition());

                $orderItems->add($orderItem);

                $em->persist($orderItem);

                $total += $item->getPrice();
            }

            foreach ($orderItems as $orderItem)
            {
                $orderItem->setOrderTotal($total);
                $em->persist($orderItem);
                $em->flush();
            }

            $order = new Order();
            $order->setOrderDate(new DateTime());
            $order->setBuyer($buyer);
            $order->setOrderNum("1797099-" . $orderNum);
            $order->setShippingAddress($shippingAddress);
            $order->setTotal($total);
            $order->setOrderItems($orderItems);

            $countryRepo = $this->getDoctrine()->getRepository(Country::class);
            $countries = $countryRepo->findAll();
            foreach ($countries as $country)
            {
                if (strpos($shippingAddress, $country->getName()) != false) {
                    $buyerCountry = $country->getName();
                    $order->setCountry($buyerCountry);
                }
            }

            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'one more Order, Database updated !');
            return $this->redirectToRoute('order_detail', ['id'=>$order->getId()]);
        }

        else
        {
            $this->addFlash('error', 'back to order please...');
            return $this->redirectToRoute('order_new');
        }

    }
}