<?php

namespace App\Repository;

use App\Entity\LikeVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LikeVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeVote[]    findAll()
 * @method LikeVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeVote::class);
    }

    // /**
    //  * @return LikeVote[] Returns an array of LikeVote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LikeVote
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
