<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

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
            ->getResult()
            ;
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

    public function  paginateItems(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.artist', 'ASC');
    }

    public function paginateSoldItems()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :sold')
            ->setParameter('sold', 'Sold')
            ->orderBy('i.artist', 'ASC');
    }

    public function paginateItemsForSale()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :forsale')
            ->setParameter('forsale', 'For Sale')
            ->orderBy('i.artist', 'ASC');
    }
}
