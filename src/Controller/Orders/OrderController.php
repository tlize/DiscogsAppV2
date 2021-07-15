<?php


namespace App\Controller\Orders;

use App\Controller\MainController;
use App\DiscogsApi\DiscogsClient;
use App\Entity\Country;
use App\Entity\Order;
use App\Pagination\MyPaginator;
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

        return $this->render('order/months.html.twig', ['periodMonths' => $periodMonths, 'nbOrdersByMonth' => $nbOrdersByMonth]);
    }

    /**
     * orders by country
     * @Route("/countries", name = "_countries")
     */
    public function Countries(EntityManagerInterface $em): Response
    {
        $countries = $em->getRepository(Order::class)->getCountryList();

        $piechart = $this->indexAction($em);

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

    public function indexAction(EntityManagerInterface  $em): PieChart
    {
        $countries = $em->getRepository(Order::class)->getBestCountries();

//        $besties = [];
//        foreach ($countries as $country) {
//            $besties[] = [$country[]['country'], $country[]['Nb']];
//        }


        $pieChart = new PieChart();
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
                [$countries[9]['country'], $countries[9]['Nb']]

//                $besties
            ]

        );
//        $pieChart->getOptions()->setTitle('Best Buying Countries');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#0069d9');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $pieChart;
    }

}