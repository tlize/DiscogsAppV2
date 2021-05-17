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
        $order = new Order();

        $orderForm = $this->createForm(OrderType::class, $order);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid())
        {
            $itemRepo = $this->getDoctrine()->getRepository(Item::class);
            $items = $itemRepo->findItemsForNewOrder();

            return  $this->render('item/neworder.html.twig', [
                'order'=>$order, 'items'=>$items
            ]);
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

                $orderItem->setOrderNum("1797099-" . $orderNum)
                    ->setBuyer($buyer)
                    ->setShippingAddress($shippingAddress)
                    ->setOrderDate(new DateTime())
                    ->setOrderTotal(0)
                    ->setStatus('')
                    ->setItemId($item->getListingId())
                    ->setItemPrice($item->getPrice())
                    ->setItemFee(0)
                    ->setDescription($description)
                    ->setReleaseId($item->getReleaseId())
                    ->setMediaCondition($item->getMediaCondition());

                $orderItems->add($orderItem);

                $em->persist($orderItem);

                $total += $item->getPrice();
            }



            $order = new Order();
            $order->setOrderDate(new DateTime())
                ->setBuyer($buyer)
                ->setOrderNum("1797099-" . $orderNum)
                ->setShippingAddress($shippingAddress)
                ->setTotal($total)
                ->setOrderItems($orderItems);

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

            foreach ($orderItems as $orderItem)
            {
                $orderItem->setOrderTotal($total)
                    ->setOrder($order);
                $em->persist($orderItem);
                $em->flush();
            }

            $this->addFlash('success', 'One more Order, Database updated !');
            return $this->redirectToRoute('order_detail', ['id'=>$order->getId()]);
        }

        else
        {
            $this->addFlash('error', 'Back to order please...');
            return $this->redirectToRoute('order_new');
        }

    }
}