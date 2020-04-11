<?php

namespace App\Repository;

/**
 * VisitRepository
 *
 *
 */
class VisitRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param \DateTime $visitDate
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countNbTicketsOnThisDate($visitDate)
    {

        $qb = $this->createQueryBuilder('v');

        $qb->select('sum(v.nbTicket)')
            ->where('v.visitDate = :visitDate')
            ->setParameter('visitDate', $visitDate);


        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

}
