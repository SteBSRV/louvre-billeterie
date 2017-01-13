<?php
// test/LATicketingBundle/Entity/OrderTest.php

namespace Tests\LATicketingBundle\Entity;

use LA\TicketingBundle\Entity\Order;
use LA\TicketingBundle\Entity\Ticket;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    public function test_creation_date_of_order()
    {
    	$order = new Order();

    	$this->assertNotNull($order->getOrderDate(), "Problème sur l'initialisation de la date de la commande.");
    }

    public function test_add_ticket_to_order()
    {
    	$order = new Order();
    	$ticket = new Ticket();

    	$order->addTicket($ticket);

    	$this->assertNotNull($order->getTickets(), "Problème sur l'ajout des Tickets.");
    }

    public function test_check_tickets_number()
    {
    	$order = new Order();
    	$ticket1 = new Ticket();
    	$ticket2 = new Ticket();

    	$order->addTicket($ticket1);
    	$order->addTicket($ticket2);

    	$this->assertEquals(2, $order->getNbTickets(), "Problème sur le calcul du nombre de tickets.");
    }

    public function test_tickets_removing()
    {
    	$order = new Order();
    	$ticket1 = new Ticket();
    	$ticket2 = new Ticket();
    	$ticket3 = new Ticket();

    	$order->addTicket($ticket1);
    	$order->addTicket($ticket2);
    	$order->addTicket($ticket3);
    	$order->removeTicket($ticket1);

    	$this->assertEquals(2, $order->getNbTickets(), "Problème sur la suppression des tickets.");
    }

    public function test_orders_validation()
    {
    	$order = new Order();
    	$this->assertEquals(false, $order->getPaid(), "Problème sur l'initialisation de la validité de la classe Command.");

    	$order->markAsPaid();
    	$this->assertEquals(true, $order->getPaid(), "Problème sur la configuration de la validité.");
    }
}
