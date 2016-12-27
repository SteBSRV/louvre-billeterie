<?php
// test/LATicketingBundle/Entity/OrderTest.php

namespace Tests\LATicketingBundle\Entity;

use LA\TicketingBundle\Entity\Order;
use LA\TicketingBundle\Entity\Ticket;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    public function testDate()
    {
    	$order = new Order();

    	$this->assertNotNull($order->getOrderDate(), "Problème sur l'initialisation de la date de la commande.");
    }

    public function testAddTicket()
    {
    	$order = new Order();
    	$ticket = new Ticket();

    	$order->addTicket($ticket);

    	$this->assertNotNull($order->getTickets(), "Problème sur l'ajout des Tickets.");
    }

    public function testNbTickets()
    {
    	$order = new Order();
    	$ticket1 = new Ticket();
    	$ticket2 = new Ticket();

    	$order->addTicket($ticket1);
    	$order->addTicket($ticket2);

    	$this->assertEquals(2, $order->getNbTickets(), "Problème sur le calcul du nombre de tickets.");
    }

    public function testRemoveTicket()
    {
    	$order = new Order();
    	$ticket1 = new Ticket();
    	$ticket2 = new Ticket();
    	$ticket3 = new Ticket();

    	$order->addTicket($ticket1);
    	$order->addTicket($ticket2);
    	$order->addTicket($ticket3);
    	$order->removeTicket($ticket1);

    	$this->assertEquals(2, $order->getnbTickets(), "Problème sur la suppression des tickets.");
    }

    public function testValid()
    {
    	$order = new order();
    	$this->assertEquals(false, $order->getPaid(), "Problème sur l'initialisation de la validité de la classe Command.");

    	$order->setPaid(true);
    	$this->assertEquals(true, $order->getPaid(), "Problème sur la configuration de la validité.");
    }
}
