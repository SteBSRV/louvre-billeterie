<?php

namespace Tests\LATicketingBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use LA\TicketingBundle\Entity\Command;
use LA\TicketingBundle\Entity\Ticket;

class CommandTest extends WebTestCase
{
    public function testDate()
    {
    	$command = new Command();

    	$this->assertNotNull($command->getDate(), "Problème sur l'initialisation de la date de la commande.");
    }

    public function testAddTicket()
    {
    	$command = new Command();
    	$ticket = new Ticket();

    	$command->addTicket($ticket);

    	$this->assertNotNull($command->getTickets(), "Problème sur l'ajout des Tickets.");
    }

    public function testNbTickets()
    {
    	$command = new Command();
    	$ticket1 = new Ticket();
    	$ticket2 = new Ticket();

    	$command->addTicket($ticket1);
    	$command->addTicket($ticket2);

    	$command->setNbTickets();

    	$this->assertEquals('2', $command->getNbTickets(), "Problème sur le calcul du nombre de tickets.");
    }

    public function testRemoveTicket()
    {
    	$command = new Command();
    	$ticket1 = new Ticket();
    	$ticket2 = new Ticket();
    	$ticket3 = new Ticket();

    	$command->addTicket($ticket1);
    	$command->addTicket($ticket2);
    	$command->addTicket($ticket3);
    	$command->removeTicket($ticket1);

    	$command->setNbTickets();

    	$this->assertEquals('2', $command->getnbTickets(), "Problème sur la suppression des tickets.");
    }

    public function testValid()
    {
    	$command = new Command();
    	$this->assertEquals(false, $command->getValid(), "Problème sur l'initialisation de la validité de la classe Command.");

    	$command->setValid(true);
    	$this->assertEquals(true, $command->getValid(), "Problème sur la configuration de la validité.");
    }
}
