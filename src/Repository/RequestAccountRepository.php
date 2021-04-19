<?php

namespace App\Repository;

use App\Entity\RequestAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestAccount[]    findAll()
 * @method RequestAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestAccount::class);
    }

}
