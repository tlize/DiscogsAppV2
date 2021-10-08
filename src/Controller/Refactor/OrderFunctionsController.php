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
        return array_reverse($months);
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
    public function indexCountry(EntityManagerInterface  $em, $region): GeoChart
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
            ->getColorAxis()->setColors(['#AA3333'])
        ;

        if ($region != '') {
            $countryChart->getOptions()->setRegion($region);
        }

        return $countryChart;
    }

    /**
     * get month graph
     */
    public function indexMonth(EntityManagerInterface $em): ColumnChart
    {
        $months = $em->getRepository(Order::class)->getMonthList();
        $monthsForGraph = [];
        $monthsForGraph[] = ['', 'Nb of Orders'];
        foreach ($months as  $month) {
            $monthsForGraph[] = [$month['month'], $month['Nb']];
        }
        $monthChart = new ColumnChart();
        $monthChart->getData()->setArrayToDataTable($monthsForGraph
//            , true
        );
        $monthChart->getOptions()->setColors(['#AA3333']);

        return $monthChart;
    }


    public function getCountriesList(): array
    {
        return array(
            "Afghanistan",
            "Åland Islands",
            "Albania",
            "Algeria",
            "American Samoa",
            "Andorra",
            "Angola",
            "Anguilla",
            "Antarctica",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Aruba",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bermuda",
            "Bhutan",
            "Bolivia, Plurinational State of",
            "Bonaire, Sint Eustatius and Saba",
            "Bosnia and Herzegovina",
            "Botswana",
            "Bouvet Island",
            "Brazil",
            "British Indian Ocean Territory",
            "Brunei Darussalam",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cape Verde",
            "Cayman Islands",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Christmas Island",
            "Cocos (Keeling) Islands",
            "Colombia",
            "Comoros",
            "Congo",
            "Congo, the Democratic Republic of the",
            "Cook Islands",
            "Costa Rica",
            "Côte d\\\"Ivoire",
            "Croatia",
            "Cuba",
            "Curaçao",
            "Cyprus",
            "Czech Republic",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Ethiopia",
            "Falkland Islands (Malvinas)",
            "Faroe Islands",
            "Fiji",
            "Finland",
            "France",
            "French Guiana",
            "French Polynesia",
            "French Southern Territories",
            "Gabon",
            "Gambia",
            "Georgia",
            "Germany",
            "Ghana",
            "Gibraltar",
            "Greece",
            "Greenland",
            "Grenada",
            "Guadeloupe",
            "Guam",
            "Guatemala",
            "Guernsey",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Heard Island and McDonald Islands",
            "Holy See (Vatican City State)",
            "Honduras",
            "Hong Kong",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran, Islamic Republic of",
            "Iraq",
            "Ireland",
            "Isle of Man",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jersey",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea, Democratic People\\\"s Republic of",
            "Korea, Republic of",
            "Kuwait",
            "Kyrgyzstan",
            "Lao People\\\"s Democratic Republic",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macao",
            "Macedonia, the Former Yugoslav Republic of",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Martinique",
            "Mauritania",
            "Mauritius",
            "Mayotte",
            "Mexico",
            "Micronesia, Federated States of",
            "Moldova, Republic of",
            "Monaco",
            "Mongolia",
            "Montenegro",
            "Montserrat",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "New Caledonia",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Niue",
            "Norfolk Island",
            "Northern Mariana Islands",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Palestine, State of",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Pitcairn",
            "Poland",
            "Portugal",
            "Puerto Rico",
            "Qatar",
            "Réunion",
            "Romania",
            "Russian Federation",
            "Rwanda",
            "Saint Barthélemy",
            "Saint Helena, Ascension and Tristan da Cunha",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Martin (French part)",
            "Saint Pierre and Miquelon",
            "Saint Vincent and the Grenadines",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Sint Maarten (Dutch part)",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "South Georgia and the South Sandwich Islands",
            "South Sudan",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Svalbard and Jan Mayen",
            "Swaziland",
            "Sweden",
            "Switzerland",
            "Syrian Arab Republic",
            "Taiwan, Province of China",
            "Tajikistan",
            "Tanzania, United Republic of",
            "Thailand",
            "Timor-Leste",
            "Togo",
            "Tokelau",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Turks and Caicos Islands",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States",
            "United States Minor Outlying Islands",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Venezuela, Bolivarian Republic of",
            "Viet Nam",
            "Virgin Islands, British",
            "Virgin Islands, U.S.",
            "Wallis and Futuna",
            "Western Sahara",
            "Yemen",
            "Zambia",
            "Zimbabwe")
        ;
    }

}