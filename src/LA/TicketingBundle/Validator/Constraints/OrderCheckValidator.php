<?php
// src/LA/TicketingBundle/Validator/Constraints/OrderCheckValidator.php

namespace LA\TicketingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class OrderCheckValidator extends ConstraintValidator
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

	public function validate($order, Constraint $constraint)
	{
        // Validation de la date de visite
		$visitDate = $order->getVisitDate()->format('d-m');
        $closedDays = array('01-05','01-11','25-12');

        $year = $order->getVisitDate()->format('Y');
        $easterDate = new \DateTime();
        $easterDate = $easterDate->setTimestamp(easter_date($year));
        $easterMonday = $easterDate->modify('+1 day');
        $easterMonday = $easterMonday->format('d-m');
        $ascension = $easterDate->modify('+38 day');
        $ascension = $ascension->format('d-m');
        $pentecote = $easterDate->modify('+11 day');
        $pentecote = $pentecote->format('d-m');

        $forbiddenDays = array('01-01',$easterMonday,'08-05',$ascension,$pentecote,'14-07','15-08','11-11');

        $visitDay = $order->getVisitDate()->format('N');

        if ($visitDay == 2 || in_array($visitDate, $closedDays)) {
            $this->context->buildViolation($constraint->messageClosedMuseum)->atPath('visitDate')->addViolation();
        } elseif ($visitDay == 7 || in_array($visitDate, $forbiddenDays)) {
            $this->context->buildViolation($constraint->messageClosedOrder)->atPath('visitDate')->addViolation();
        }

        // Validation du type de billet pour le jour même
        $now = new \DateTime('now');
        $today = $now->format('Y-m-d');
        $hour = $now->format('H');
        $visitDate = $order->getVisitDate()->format('Y-m-d');
        $ticketsType = $order->getTicketsType();

        if ($hour >= 14 && $ticketsType == 'journée' && ($visitDate == $today)) {
            $this->context->buildViolation($constraint->messageTooLateForFullDay)->atPath('ticketsType')->addViolation();
        }

        // Validation des tickets
        $tickets = $order->getTickets();
        $ticketsRepo = $this->em->getRepository('LATicketingBundle:Ticket');
        $nbTodayTickets = $ticketsRepo->getNbTicketsPerDay();
        // Si aucun tickets :
        if ($tickets->isEmpty()) {
            $this->context->buildViolation($constraint->messageNoTickets)->atPath('tickets')->addViolation();
        }
        // Si nb de tickets vendus supérieur à 1000
        if (($nbTodayTickets + $order->getNbTickets()) > 1000) {
            $this->context->buildViolation($constraint->messageNoMoreTickets)->atPath('tickets')->addViolation();
        }
	}
}