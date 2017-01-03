<?php
// src/LA/TicketingBundle/Validator/Constraints/OrderCheckValidator.php

namespace LA\TicketingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use LA\TicketingBundle\Entity\Order;

class OrderCheckValidator extends ConstraintValidator
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

	public function validate($order, Constraint $constraint)
	{
		$this->dateIsValid($order->getVisitDate(), $constraint);

        $this->fullDayValidation($order, $constraint);

        $this->ticketsValidation($order, $constraint);   
	}

    public function getHolidayDays($orderVisitDate)
    {
        $year = $orderVisitDate->format('Y');
        $easterDate = new \DateTime();
        $easterDate = $easterDate->setTimestamp(easter_date($year));
        $easterMonday = $easterDate->modify('+1 day');
        $easterMonday = $easterMonday->format('d-m');
        $ascension = $easterDate->modify('+38 day');
        $ascension = $ascension->format('d-m');
        $pentecote = $easterDate->modify('+11 day');
        $pentecote = $pentecote->format('d-m');

        return ['01-01',$easterMonday,'08-05',$ascension,$pentecote,'14-07','15-08','11-11'];
    }

    public function getClosedDays()
    {
        return ['01-05','01-11','25-12'];
    }

    public function dateIsValid($orderVisitDate, $constraint)
    {
        $visitDate = $orderVisitDate->format('d-m');
        $visitDay = $orderVisitDate->format('N');
        $closedDays = $this->getClosedDays();
        $forbiddenDays = $this->getHolidayDays($orderVisitDate);

        if ($visitDay == 2 || in_array($visitDate, $closedDays)) {
            $this->context->buildViolation($constraint->messageClosedMuseum)->atPath('visitDate')->addViolation();
        } elseif ($visitDay == 7 || in_array($visitDate, $forbiddenDays)) {
            $this->context->buildViolation($constraint->messageClosedOrder)->atPath('visitDate')->addViolation();
        }
    }

    public function fullDayValidation(Order $order, $constraint)
    {
        $now = new \DateTime('now');
        $today = $now->format('Y-m-d');
        $hour = $now->format('H');
        $visitDate = $order->getVisitDate()->format('Y-m-d');
        $ticketsType = $order->getTicketsType();

        if ($hour >= 14 && $ticketsType == 'journée' && ($visitDate == $today)) {
            $this->context->buildViolation($constraint->messageTooLateForFullDay)->atPath('ticketsType')->addViolation();
        }
    }

    public function ticketsValidation(Order $order, $constraint)
    {
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
