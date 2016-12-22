<?php

namespace LA\TicketingBundle\Repository;

/**
 * TicketRepository
 */
class TicketRepository extends \Doctrine\ORM\EntityRepository
{
	public function getNbTicketsPerDay()
	{
		$startDay = \DateTime::createFromFormat( "Y-m-d H:i:s", date("Y-m-d 00:00:00") );
		$endDay = \DateTime::createFromFormat( "Y-m-d H:i:s", date("Y-m-d 23:59:59") );

		$qb = $this
			->createQueryBuilder('t')
			->select('COUNT(t)')
			->leftJoin('t.order', 'o')
			->where('o.visitDate >= :start_day')
			->andWhere('o.visitDate <= :end_day')
			->andWhere('t.validationCode IS NOT NULL')
			->setParameter('start_day', $startDay)
			->setParameter('end_day', $endDay)
		;

		return $qb->getQuery()->getSingleScalarResult();
	}
}
