<?php
// test/LATicketingBundle/Entity/TicketTest.php

namespace Tests\LATicketingBundle\Entity;

use LA\TicketingBundle\Entity\Ticket;
use LA\TicketingBundle\Entity\Order;

class TicketTest extends \PHPUnit_Framework_TestCase
{
    public function testPrices()
    {
        $order = new Order();
        $order->setTicketsType('journée');
        $ticket = new Ticket();
        $ticket->setOrder($order);

    	$this->assertEquals(0, Ticket::PRICE_FREE, "Problème sur la configuration du tarif gratuit.");
        $this->assertNotNull(Ticket::PRICE_NORMAL, "Problème sur la configuration des tarifs.");

        // Senior
        $ticket->setBirthDate(new \DateTime('1950-01-01'));
        $ticket->setReduced(false);
        $this->assertEquals(1200, $ticket->getPrice(), "Problème sur le calcul du tarif.");

        // Reduced price
        $ticket->setReduced(true);
        $this->assertEquals(1000, $ticket->getPrice(), "Problème sur le calcul du tarif réduit.");
    }

    public function testValidationCode()
    {
    	$ticket = new Ticket();
    	$ticket->generateValidationCode();

    	$this->assertNotNull($ticket->getValidationCode(), "Problème sur la création du code de validation.");
    }

    public function testOrder()
    {
    	$ticket = new Ticket();
    	$order = new Order();

    	$ticket->setOrder($order);

    	$this->assertNotNull($ticket->getOrder(), "Problème sur l'association du ticket à une commande.");
    }

    public function testUsed()
    {
        $ticket = new Ticket();

        $this->assertEquals(false, $ticket->getUsed(), "Problème sur l'initialisation du status 'utilisé' du billet.");
    }
}
