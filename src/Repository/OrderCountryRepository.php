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

    /*
    public function findOneBySomeField($value): ?OrderCountry
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
