<?php

namespace App\Repository;

use App\Entity\OrderCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderCountry|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderCountry|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderCountry[]    findAll()
 * @method OrderCountry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderCountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderCountry::class);
    }

    // /**
    //  * @return OrderCountry[] Returns an array of OrderCountry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByOrderId($orderId)
    {
        return $this->createQueryBuilder('oc')
            ->andWhere('oc.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findBestCountries()
    {
        return $this->createQueryBuilder('oc')
            ->addSelect('oc.country')
            ->addSelect('COUNT(oc.orderId) AS nbOrders')
            ->addSelect('SUM(oc.total) AS totalAmount')
            ->addSelect('AVG(oc.total) AS avgAmount')
            ->groupBy('oc.country')
            ->orderBy('nbOrders', 'DESC')
            ->addOrderBy('totalAmount', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
