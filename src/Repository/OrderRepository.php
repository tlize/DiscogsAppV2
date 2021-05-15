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


    public function getLatestOrders()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.orderDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function paginateAllWithDetails()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.orderDate', 'DESC')
            ->join('o.orderItems', 'oi')
            ->addSelect('oi')
            ;
    }

    public function findBestCountries()
    {
        $em = $this->getEntityManager();
        $dql = "
            SELECT o.country, COUNT(o.orderNum) AS nbOrders, SUM(o.total) AS totalAmount, AVG(o.total) AS avgAmount
                FROM App\Entity\Order o 
                GROUP BY o.country
                ORDER BY nbOrders DESC 
        ";
        $query = $em->createQuery($dql);
        return $query->getResult();
    }

}
