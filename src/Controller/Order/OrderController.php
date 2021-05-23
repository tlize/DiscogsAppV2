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
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Order::class)->findAllWithDetails();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );
        return $this->render('order/list.html.twig', ['orders' => $orders]);
    }


    /**
     * order details
     * @Route("/order/{id}", name = "order_detail",
     *     requirements={"id" : "\d+"},
     *     methods={"GET"})
     */
    public function detail(EntityManagerInterface $em, $id): Response
    {
        $order = $em->getRepository(Order::class)->find($id);

        if (empty($order)) {
            throw $this->createNotFoundException("Order not found !");
        }

        $items = new ArrayCollection();
        $orderItems = $order->getOrderItems();

        foreach ($orderItems as $orderItem)
        {
            $listingId = $orderItem->getItemId();
            $item = $em->getRepository(Item::class)->findOrderItem($listingId);
            $items->add($item);
        }

        return $this->render('order/detail.html.twig', ['order' => $order, 'items' => $items]);
    }


    /**
     * best buying countries
     * @Route("/countries", name = "best_countries_list")
     */
    public function bestCountries(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->getRepository(Order::class)->findBestCountries();

        $bestCountries = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('best/countries.html.twig', ['bestCountries' => $bestCountries]);
    }


    /**
     * new order form
     * for buyer info and order nb
     * @Route("/order/new", name="order_new")
     */
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $order = new Order();

        $orderForm = $this->createForm(OrderType::class, $order);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $items = $em->getRepository(Item::class)->findItemsForNewOrder();

            return $this->render('item/neworder.html.twig', [
                'order' => $order, 'items' => $items
            ]);
        }

        return $this->render('order/form.html.twig', [
            'orderForm' => $orderForm->createView()
        ]);

    }


    /**
     * confirm order
     * data treatment before updating db
     * @Route("/order/confirm", name="order_confirm")
     */
    public function confirmOrder(EntityManagerInterface $em): RedirectResponse
    {
        if (isset($_POST['id']) && isset($_POST['buyer']) && isset($_POST['orderNum']) && isset($_POST['shippingAddress'])) {
            $buyer = $_POST['buyer'];
            $orderNum = $_POST['orderNum'];
            $shippingAddress = $_POST['shippingAddress'];

            $total = 0;
            $orderItems = new ArrayCollection();

            // fetch items from ids, total calculation
            foreach ($_POST['id'] as $id) {
                $item = $em->getRepository(Item::class)->find($id);
                // update item status
                $item->setStatus('Sold');
                $em->persist($item);
                $em->flush();

                $description = $item->getArtist() . " - " . $item->getTitle() . " (" . $item->getFormat() . ")";

                $orderItem = new OrderItem();

                // concatenation with prefix for orderNum : my seller number (as seen in every other order)
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

                // new orderItem
                $em->persist($orderItem);

                $total += $item->getPrice();
            }

            // order initialisation
            $order = new Order();
            $order->setOrderDate(new DateTime())
                ->setBuyer($buyer)
                ->setOrderNum("1797099-" . $orderNum)
                ->setShippingAddress($shippingAddress)
                ->setTotal($total)
                // OneToMany relationship
                ->setOrderItems($orderItems);
            $order->setNbItems($orderItems->count());

            // set country from shipping address
            $countries = $em->getRepository(Country::class)->findAll();

            foreach ($countries as $country) {
                if (strpos($shippingAddress, $country->getName()) != false) {
                    $buyerCountry = $country->getName();
                    $order->setCountry($buyerCountry);
                }
            }

            if ($order->getCountry() == null) {
                $order->setCountry('unknown');
            }

            // order created
            $em->persist($order);
            $em->flush();

            foreach ($orderItems as $orderItem) {
                // update total, set order linked to orderItem
                $orderItem->setOrderTotal($total)
                    // ManyToOne relationship
                    ->setOrder($order);
                $em->persist($orderItem);
                $em->flush();
            }

            // get to order detail page with generated id
            $this->addFlash('success', 'One more Order, Database updated !');
            return $this->redirectToRoute('order_detail', ['id' => $order->getId()]);
        } else {
            // back to new order form
            $this->addFlash('error', 'Back to order please...');
            return $this->redirectToRoute('order_new');
        }

    }
}