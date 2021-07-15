<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }


    public function getOrderList($orderNums)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.orderNum in (:orderNums)')
            ->setParameter('orderNums', $orderNums)
            ->addOrderBy('o.orderNum', 'desc')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getMonthList()
    {
        return $this->createQueryBuilder('o')
            ->addSelect('o.month')
            ->addSelect('COUNT(o.id) AS Nb')
            ->addGroupBy('o.month')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCountryList()
    {
        return $this->createQueryBuilder('o')
            ->addSelect('o.country')
            ->addSelect('COUNT(o.id) AS Nb')
            ->addGroupBy('o.country')
            ->addOrderBy('Nb', 'desc')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getOneCountryOrders($country)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.country = :country')
            ->setParameter('country', $country)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBestCountries()
    {
        return $this->createQueryBuilder('o')
            ->addSelect('o.country')
            ->addSelect('COUNT(o.id) AS Nb')
            ->addGroupBy('o.country')
            ->addOrderBy('Nb', 'desc')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

}
