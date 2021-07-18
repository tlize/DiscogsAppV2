<?php


namespace App\Controller\Orders;

use App\Controller\Refactor\MainFunctionsController;
use App\Controller\Refactor\OrderFunctionsController;
use App\DiscogsApi\DiscogsClient;
use App\Entity\Country;
use App\Entity\Order;
use App\Pagination\MyPaginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order", name = "order")
 */
class OrderController extends AbstractController
{
    //lists////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * all orders
     * @Route("/", name = "_list")
     */
    public function list(MainFunctionsController $mc,OrderFunctionsController $oc, DiscogsClient $dc, EntityManagerInterface $em, MyPaginator $paginator): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('id');
        $sortOrder = $mc->getSortOrder('desc');
        $sortLink = $mc->getSortLink();

        $orders = $dc->getDiscogsClient()->getMyOrders($page, 50, 'All', $sort, $sortOrder);
        $pagination = $paginator->paginate($orders, $page);
        $orderCountries = $oc->getOrdersCountries($em, $orders);

        return $this->render('order/list.html.twig', ['orders' => $orders, 'orderCountries' => $orderCountries,
            'sortLink' => $sortLink, 'pagination' => $pagination]);
    }

    /**
     * orders by month
     * @Route("/months", name = "_months")
     */
    public function Months(OrderFunctionsController $oc, EntityManagerInterface $em): Response
    {
        $periodMonths = $oc->getOrdersMonths();
        $dbMonths = $em->getRepository(Order::class)->getMonthList();

        $nbOrdersByMonth = [];
        foreach ($dbMonths as $dbMonth) {
            $name = $dbMonth['month'];
            $nbOrders = $dbMonth['Nb'];
            $nbOrdersByMonth[$name] = $nbOrders;
        }

        $monthchart = $oc->indexMonth($em);

        return $this->render('order/months.html.twig', ['periodMonths' => $periodMonths, 'nbOrdersByMonth' => $nbOrdersByMonth, 'monthchart' => $monthchart]);
    }

    /**
     * orders by country
     * @Route("/countries", name = "_countries")
     */
    public function Countries(MainFunctionsController $mc, OrderFunctionsController $oc, EntityManagerInterface $em): Response
    {
        $region = $mc->getRegionMap();
        $countries = $em->getRepository(Order::class)->getCountryList();
        $countrychart = $oc->indexCountry($em, $region);

        return $this->render('order/countries.html.twig', ['countries' => $countries, 'countrychart' => $countrychart]);
    }

    /**
     * orders for one month
     * @Route("/month/{monthName}", name = "_month")
     */
    public function MonthOrders(EntityManagerInterface $em, $monthName, DiscogsClient $dc, OrderFunctionsController $oc): Response
    {
        $months = $oc->getOrdersMonths();
        $month = $months[$monthName];
        $orders = $dc->getMyDiscogsClient()->getOrdersByMonth($month['created_after'], $month['created_before']);
        $orderCountries = $oc->getOrdersCountries($em, $orders);

        return $this->render('order/month_detail.html.twig', ['name' => $monthName, 'orders' => $orders, 'orderCountries' => $orderCountries]);
    }

    /**
     * orders for one country
     * @Route("/country/{country}", name = "_country")
     */
    public function CountryOrders(EntityManagerInterface $em, $country): Response
    {
        $countryOrders = $em->getRepository(Order::class)->getOneCountryOrders($country);

        $orderNums = [];
        foreach ($countryOrders as $order) {
            $orderNum = $order->getorderNum();
            $orderNums[] = $orderNum;
        }

        return $this->render('order/country_detail.html.twig', ['orderNums' => $orderNums, 'country' => $country]);
    }


    //details////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * order details
     * @Route("/{id}", name = "_detail",
     *     methods={"GET"})
     */
    public function detail(DiscogsClient $dc,$id): Response
    {
        $order = $dc->getDiscogsClient()->orderWithId($id);

        if (empty($order)) {
            throw $this->createNotFoundException("Order not found !");
        }

        return $this->render('order/detail.html.twig', ['order' => $order]);
    }

////click on 'set Country'///////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * set country from shipping address
     * using Country table
     * @Route("/{id}/dbcreate", name = "_db_create")
     */
    public function createDbOrder(DiscogsClient $dc, EntityManagerInterface $em, $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $countries = $em->getRepository(Country::class)->findAll();

        $order = $dc->getDiscogsClient()->orderWithId($id);
        $address = $order->shipping_address;

        if (substr($order->status, 0, 9) == 'Cancelled') {
            $this->addFlash('warning', 'No country can be set if order is cancelled !');
        }
        else {
            $dbOrder = new Order();
            foreach ($countries as $country) {
                if (strpos($address, $country->getName()) != false) {
                    $buyerCountry = $country->getName();
                    if ($buyerCountry == 'Russian Federation') {
                        $buyerCountry = 'Russia';
                    }
                    $dbOrder->setCountry($buyerCountry);
                }
            }
            $dbOrder->setOrderNum($id);
            $dbOrder->setMonth(substr($order->created, 0, 7));
            $em->persist($dbOrder);
            $em->flush();
            $this->addFlash('success', 'Cool, order ' . $id . ' is now in database with country 
            (' . $dbOrder->getCountry() . ') and month (' . $dbOrder->getMonth() . ')');
        }

        return $this->render('order/detail.html.twig', ['order' => $order]);
    }




}