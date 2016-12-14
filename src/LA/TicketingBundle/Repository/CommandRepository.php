<?php

namespace LA\TicketingBundle\Repository;

/**
 * CommandRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandRepository extends \Doctrine\ORM\EntityRepository
{
	public function getTicketsFromCommand(Command $command)
	{
		$qb = $this
		  ->createQueryBuilder('c')
		  ->where('c.id = :id')
		  ->setParameter('id', $command->getId())
		  ->leftJoin('c.tickets', 'tickets')
		  ->addSelect('tickets')
		;

		return $qb
		         ->getQuery()
		         ->getResult()
		       ;
	}
}
