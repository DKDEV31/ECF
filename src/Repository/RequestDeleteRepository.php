<?php

namespace App\Repository;

use App\Entity\RequestDelete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestDelete|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestDelete|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestDelete[]    findAll()
 * @method RequestDelete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestDeleteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestDelete::class);
    }

    // /**
    //  * @return RequestDelete[] Returns an array of RequestDelete objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequestDelete
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
