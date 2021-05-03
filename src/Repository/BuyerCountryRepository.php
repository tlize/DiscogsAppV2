<?php

namespace App\Repository;

use App\Entity\BuyerCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BuyerCountry|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuyerCountry|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuyerCountry[]    findAll()
 * @method BuyerCountry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyerCountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuyerCountry::class);
    }

    // /**
    //  * @return BuyerCountry[] Returns an array of BuyerCountry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BuyerCountry
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
