<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Order;
use App\Entity\OrderItem;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::Create();

        $format = Array(
            'LP', '2LP', 'Single', 'EP'
        );
        $mediaCondition = Array(
            'M', 'NM', 'VG+', 'VG'
        );

        //items
        $items= new ArrayCollection();

        for ($i = 0; $i < 500; $i++) {
            $item = new Item();
            $item->setListingId($faker->numberBetween())
                ->setArtist($faker->firstName . ' ' . $faker->lastName)
                ->setLabel($faker->company)
                ->setCatno($faker->regexify('[A-Z]{5}[0-4]{3}'))
                ->setFormat($faker->randomElement($format))
                ->setReleaseId($faker->numberBetween(0000001, 9999999))
                ->setPrice($faker->numberBetween(3, 50))
                ->setTitle($faker->words(5, true))
                ->setMediaCondition($faker->randomElement($mediaCondition))
                ->setStatus('For Sale')
                ->setListed(new DateTime());

            $items->add($item);
            $manager->persist($item);
            $manager->flush();
        }

        $orderNum = 1;
        for ($o = 0; $o < 200; $o++) {
            $order = new Order();
            $total = 0;
            $pickedItem = 0;
            $order->setBuyer($faker->userName)
                ->setOrderNum('666666-' . $orderNum)
                ->setShippingAddress($faker->address)
                ->setCountry($faker->country)
                ->setOrderDate(new DateTime())
                ->setNbItems(rand(1,3));

            $orderItems = new ArrayCollection();
            // pick items to become orderItems
            for ($oi = 0; $oi < $order->getNbItems(); $oi++) {
                $orderItem = new OrderItem();
                $item = $items[rand(0, $items->count() - 1)];
                if ($item->getStatus() == 'For Sale') {
                    $orderItem->setOrderNum($order->getOrderNum())
                        ->setBuyer($order->getBuyer())
                        ->setShippingAddress($order->getShippingAddress())
                        ->setOrderDate(new DateTime())
                        ->setOrderTotal(0)
                        ->setStatus('')
                        ->setItemId($item->getListingId())
                        ->setItemPrice($item->getPrice())
                        ->setItemFee(0)
                        ->setDescription($item->getArtist() . ' - ' . $item->getTitle())
                        ->setReleaseId($item->getReleaseId())
                        ->setMediaCondition($item->getMediaCondition())
                        ->setItemPrice($item->getPrice());

                    $total +=$orderItem->getItemPrice();
                    $pickedItem++;
                    $item->setStatus('Sold');
                    $orderItems->add($orderItem);
                    $manager->persist($orderItem);
                }

            }
            $order->setTotal($total)
                ->setOrderItems($orderItems)
            ;

            foreach ($orderItems as $orderItem) {
                $orderItem->setOrderTotal($total)
                    ->setOrder($order);
            }

            $manager->persist($order);
            $orderNum++;
        }

        foreach ($items as $item) {
            $manager->persist($item);
        }



        $manager->flush();
    }
}

// php bin/console doctrine:fixtures:load
// sql, table order_item : UPDATE `order_item` SET order_id = substr(order_num, 8)
