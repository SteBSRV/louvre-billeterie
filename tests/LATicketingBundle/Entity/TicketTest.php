<?php
// test/LATicketingBundle/Entity/TicketTest.php

namespace Tests\LATicketingBundle\Entity;

use LA\TicketingBundle\Entity\Ticket;
use LA\TicketingBundle\Entity\Order;

class TicketTest extends \PHPUnit_Framework_TestCase
{
    public function testPrices()
    {
    	$this->assertEquals(0, Ticket::PRICE_FREE, "Problème sur la configuration du tarif gratuit.");
        $this->assertNotNull(Ticket::PRICE_NORMAL, "Problème sur la configuration des tarifs.");
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

    public function testUser()
    {
        $ticket = new Ticket();

        $this->assertEquals(false, $ticket->getUsed(), "Problème sur l'initialisation du status 'utilisé' du billet.");
    }
}
