<?php


namespace App\Controller\Refactor;


use App\Entity\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\GeoChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderFunctionsController extends AbstractController
{

    /**
     * get all months from first order to today
     */
    public function getOrdersMonths(): array
    {
        $period = CarbonPeriod::create(Carbon::create('2019', '09', '22'), Carbon::now());
        $months = [];
        foreach ($period as $date) {
            $yearMonth = substr($date, 0, 7);
            $year = explode('-', $yearMonth)[0];
            $month = explode('-', $yearMonth)[1];
            $current = Carbon::create($year, $month);
            $months[$yearMonth]['name'] = $yearMonth;
            $months[$yearMonth]['created_after'] = Carbon::create($year, $month, 1, 00, 00, 00)
                    ->format('Y-m-d') . 'T00:00:00Z';
            $months[$yearMonth]['created_before'] = $current->endOfMonth()
                    ->format('Y-m-d') . 'T23:59:59Z';
        }
        return $months;
    }

    /**
     * get country from db for all orders in current $orders
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