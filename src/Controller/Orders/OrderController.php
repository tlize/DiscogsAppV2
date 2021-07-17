<?php


namespace App\Controller\Orders;

use App\Controller\MainController;
use App\DiscogsApi\DiscogsClient;
use App\Entity\Country;
use App\Entity\Order;
use App\Pagination\MyPaginator;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\GeoChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
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

        dump($monthchart, $dbMonths);
        return $this->render('order/months.html.twig', ['periodMonths' => $periodMonths, 'nbOrdersByMonth' => $nbOrdersByMonth, 'monthchart' => $monthchart]);
    }

    /**
     * orders by country
     * @Route("/countries", name = "_countries")
     */
    public function Countries(EntityManagerInterface $em): Response
    {
        $countries = $em->getRepository(Order::class)->getCountryList();

        $piechart = $this->indexCountry($em);

        dump($countries, $piechart);
        return $this->render('order/countries.html.twig', ['countries' => $countries, 'piechart' => $piechart]);
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
    public function indexCountry(EntityManagerInterface  $em): PieChart
    {
        $countries = $em->getRepository(Order::class)->getCountryList();

//        $besties = [];
//        foreach ($countries as $country) {
//            $besties[] = [$country[]['country'], $country[]['Nb']];
//        }

        $pieChart = new PieChart();
//        $pieChart = new GeoChart();
        $pieChart->getData()->setArrayToDataTable(
            [
                ['Country', 'Nb of orders'],
                [$countries[0]['country'], $countries[0]['Nb']],
                [$countries[1]['country'], $countries[1]['Nb']],
                [$countries[2]['country'], $countries[2]['Nb']],
                [$countries[3]['country'], $countries[3]['Nb']],
                [$countries[4]['country'], $countries[4]['Nb']],
                [$countries[5]['country'], $countries[5]['Nb']],
                [$countries[6]['country'], $countries[6]['Nb']],
                [$countries[7]['country'], $countries[7]['Nb']],
                [$countries[8]['country'], $countries[8]['Nb']],
                [$countries[9]['country'], $countries[9]['Nb']],
                [$countries[10]['country'], $countries[10]['Nb']],
                [$countries[11]['country'], $countries[11]['Nb']],
                [$countries[12]['country'], $countries[12]['Nb']],
                [$countries[13]['country'], $countries[13]['Nb']],
                [$countries[14]['country'], $countries[14]['Nb']],
                [$countries[15]['country'], $countries[15]['Nb']],
                [$countries[16]['country'], $countries[16]['Nb']],
                [$countries[17]['country'], $countries[17]['Nb']],
                [$countries[18]['country'], $countries[18]['Nb']],
                [$countries[19]['country'], $countries[19]['Nb']],
                [$countries[20]['country'], $countries[20]['Nb']],
                [$countries[21]['country'], $countries[21]['Nb']],
                [$countries[22]['country'], $countries[22]['Nb']],
                [$countries[23]['country'], $countries[23]['Nb']],
                [$countries[24]['country'], $countries[24]['Nb']],
                [$countries[25]['country'], $countries[25]['Nb']]
//                $besties
            ]

        );
//        $pieChart->getOptions()->getColorAxis()->setColors(['blue']);
        $pieChart->getOptions()->setWidth(900)->setHeight(500);

        return $pieChart;
    }





    /**
     * get month graph
     */
    public function indexMonth(EntityManagerInterface $em): ColumnChart
    {

        $months = $em->getRepository(Order::class)->getMonthList();

        $monthChart = new ColumnChart();

//        $monthChart->getData()->setArrayToDataTable(['Month', 'Orders']);
//        foreach ($months as  $month) {
//            $monthChart->getData()->setArrayToDataTable([$month['month'], $month['Nb']]);
//        }

        $monthChart->getData()->setArrayToDataTable([
            ['', 'Orders'],
            [$months[0]['month'], $months[0]['Nb']],
            [$months[1]['month'], $months[1]['Nb']],
            [$months[2]['month'], $months[2]['Nb']],
            [$months[3]['month'], $months[3]['Nb']],
            [$months[4]['month'], $months[4]['Nb']],
            [$months[5]['month'], $months[5]['Nb']],
            [$months[6]['month'], $months[6]['Nb']],
            [$months[7]['month'], $months[7]['Nb']],
            [$months[8]['month'], $months[8]['Nb']],
            [$months[9]['month'], $months[9]['Nb']],
            [$months[10]['month'], $months[10]['Nb']],
            [$months[11]['month'], $months[11]['Nb']],
            [$months[12]['month'], $months[12]['Nb']],
            [$months[13]['month'], $months[13]['Nb']],
            [$months[14]['month'], $months[14]['Nb']],
            [$months[15]['month'], $months[15]['Nb']],
            [$months[16]['month'], $months[16]['Nb']],
            [$months[17]['month'], $months[17]['Nb']],
            [$months[18]['month'], $months[18]['Nb']],
            [$months[19]['month'], $months[19]['Nb']],
            [$months[20]['month'], $months[20]['Nb']],
            [$months[21]['month'], $months[21]['Nb']]
//            ,[$months[22]['month'], $months[22]['Nb']]
//            $months
        ]);
        $monthChart->getOptions()
            ->setBars('vertical')
//            ->setHeight(400)
//            ->setWidth(900)
            ->setColors(['#0069d9'])
            ->getVAxis();

        return $monthChart;
    }

}