<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findOutOfShop()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status != :sold')
            ->setParameter('sold', 'Sold')
            ->andWhere('i.status != :forSale')
            ->setParameter('forSale', 'for Sale')
            ->orderBy('i.artist', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findArtistDetail($artist)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.artist LIKE :artist')
            ->setParameter('artist', $artist)
            ->orderBy('i.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLabelDetail($label)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.label LIKE :label')
            ->setParameter('label', $label)
            ->orderBy('i.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function paginateItems(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.artist', 'ASC');
    }

    public function paginateSoldItems(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :sold')
            ->setParameter('sold', 'Sold')
            ->orderBy('i.artist', 'ASC');
    }

    public function paginateItemsForSale(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :forsale')
            ->setParameter('forsale', 'For Sale')
            ->orderBy('i.artist', 'ASC');
    }

    public function findBestArtists()
    {
        $em = $this->getEntityManager();
        $dql = "
            SELECT i.artist, COUNT(i.listingId) AS nbItems, SUM(i.price) AS total
                FROM App\Entity\Item i 
                WHERE i.status = 'Sold' 
                GROUP BY i.artist
                ORDER BY total DESC 
        ";
        return $em->createQuery($dql)->getResult();
    }

    public function findBestLabels()
    {
        $em = $this->getEntityManager();
        $dql = "
            SELECT i.label, COUNT(i.listingId) AS nbItems, SUM(i.price) AS total
                FROM App\Entity\Item i 
                WHERE i.status = 'Sold' 
                GROUP BY i.label
                ORDER BY total DESC 
        ";
        return $em->createQuery($dql)->getResult();
    }

    public function findItemsForNewOrder()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :forsale')
            ->setParameter('forsale', 'For Sale')
            ->orderBy('i.artist', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOrderItem ($listingId)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.listingId = :listingId')
            ->setParameter('listingId', $listingId)
            ->getQuery()
            ->getResult();
    }

}
