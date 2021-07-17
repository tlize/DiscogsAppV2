<?php


namespace App\Controller\Orders;

use App\Controller\MainController;
use App\DiscogsApi\DiscogsClient;
use App\Entity\Country;
use App\Entity\Order;
use App\Pagination\MyPaginator;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\GeoChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
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
    public function list(MainController $mc, DiscogsClient $dc, EntityManagerInterface $em, MyPaginator $paginator): Response
    {
        $page = $mc->getPage();
        $sort = $mc->getSort('id');
        $sortOrder = $mc->getSortOrder('desc');
        $sortLink = $mc->getSortLink();

        $orders = $dc->getDiscogsClient()->getMyOrders($page, 50, 'All', $sort, $sortOrder);
        $pagination = $paginator->paginate($orders, $page);
        $orderCountries = $this->getOrdersCountries($em, $orders);

        return $this->render('order/list.html.twig', ['orders' => $orders, 'orderCountries' => $orderCountries,
            'sortLink' => $sortLink, 'pagination' => $pagination]);
    }

    /**
     * orders by month
     * @Route("/months", name = "_months")
     */
    public function Months(MainController $mc, EntityManagerInterface $em): Response
    {
        $periodMonths = $mc->getOrdersMonths();
        $dbMonths = $em->getRepository(Order::class)->getMonthList();

        $nbOrdersByMonth = [];
        foreach ($dbMonths as $dbMonth) {
            $name = $dbMonth['month'];
            $nbOrders = $dbMonth['Nb'];
            $nbOrdersByMonth[$name] = $nbOrders;
        }

        $monthchart = $this->indexMonth($em);

        return $this->render('order/months.html.twig', ['periodMonths' => $periodMonths, 'nbOrdersByMonth' => $nbOrdersByMonth, 'monthchart' => $monthchart]);
    }

    /**
     * orders by country
     * @Route("/countries", name = "_countries")
     */
    public function Countries(EntityManagerInterface $em): Response
    {
        $countries = $em->getRepository(Order::class)->getCountryList();
        $countrychart = $this->indexCountry($em);

        return $this->render('order/countries.html.twig', ['countries' => $countries, 'countrychart' => $countrychart]);
    }

    /**
     * orders for one month
     * @Route("/month/{monthName}", name = "_month")
     */
    public function MonthOrders(EntityManagerInterface $em, $monthName, DiscogsClient $dc, MainController $mc): Response
    {
        $months = $mc->getOrdersMonths();
        $month = $months[$monthName];
        $orders = $dc->getMyDiscogsClient()->getOrdersByMonth($month['created_after'], $month['created_before']);
        $orderCountries = $this->getOrdersCountries($em, $orders);

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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * set country from shipping address
     * using Country table
     * @Route("/{id}/dbcreate", name = "_db_create")
     */
    public function createDbOrder(DiscogsClient $dc, EntityManagerInterface $em, $id): Response
    {
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


    //no route////////////////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * get country from db for all orders in $orders
     */
    public function getOrdersCountries(EntityManagerInterface $em, $orders): array
    {
        $orderNums = [];
        foreach ($orders->orders as $order) {
            $orderNum = $order->id;
            $orderNums[] = $orderNum;
        }
        $dbOrders = $em->getRepository(Order::class)->getOrderList($orderNums);
        $orderCountries = [];
        foreach ($dbOrders as $dbOrder) {
            $orderCountries[$dbOrder->getOrderNum()] = $dbOrder->getCountry();
        }
        return $orderCountries;
    }



    /**
     * get country graph
     */
    public function indexCountry(EntityManagerInterface  $em): GeoChart
    {
        $countries = $em->getRepository(Order::class)->getCountryList();
        $bestCountries = [];
//        $bestCountries[] = ['Country', 'Nb of orders'];
        foreach ($countries as $country) {
            $bestCountries[] = [$country['country'], $country['Nb']];
        }
        $countryChart = new GeoChart();
        $countryChart->getData()->setArrayToDataTable($bestCountries
            , true
        );
        $countryChart->getOptions()
            ->setWidth(900)
            ->setHeight(500)
            ->setRegion(150)
            ->getColorAxis()->setColors(['#0069d9']);

        return $countryChart;
    }





    /**
     * get month graph
     */
    public function indexMonth(EntityManagerInterface $em): ColumnChart
    {
        $months = $em->getRepository(Order::class)->getMonthList();
        $monthsForGraph = [];
        foreach ($months as  $month) {
            $monthsForGraph[] = [$month['month'], $month['Nb']];
        }
        $monthChart = new ColumnChart();
        $monthChart->getData()->setArrayToDataTable($monthsForGraph, true);
        $monthChart->getOptions()
            ->setBars('vertical')
            ->setColors(['#0069d9']);

        return $monthChart;
    }

}