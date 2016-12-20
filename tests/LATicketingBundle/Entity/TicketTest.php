<?php

namespace Tests\LATicketingBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use LA\TicketingBundle\Entity\Ticket;
use LA\TicketingBundle\Entity\Command;

class TicketTest extends WebTestCase
{
    public function testPrices()
    {
    	$this->assertEquals('0', Ticket::PRICE_FREE, "Problème sur la configuration du tarif gratuit.");
        $this->assertNotNull(Ticket::PRICE_NORMAL, "Problème sur la configuration des tarifs.");
    }

    public function testVisitDate()
    {
    	$ticket = new Ticket();

    	$this->assertNotNull($ticket->getVisitDate(), "Problème sur l'initialisation du type DateTime pour la date de visite");
    }

    public function testValidationCode()
    {
    	$ticket = new Ticket();
    	$validationCode = $ticket->setValidationCode();

    	$this->assertNotNull($validationCode, "Problème sur la création du code de validation.");
    }

    public function testSetPrice()
    {
    	$ticket = new Ticket();
    	$ticket->setVisitDate(new \DateTime('tomorrow'));

    	// BirthDate Today -> Category younger than 4 years -> 0€
    	$birthDate = new \DateTime('now');
    	$ticket->setBirthDate($birthDate);
    	$ticket->setPrice();

    	$this->assertEquals('0', $ticket->getPrice(), "Problème sur l'attribution du prix.");

    	// BirthDate more than 65years -> Category senior -> 12.00€
    	$birthDate = new \DateTime('65 years ago');
    	$ticket->setBirthDate($birthDate);
    	$ticket->setPrice();

    	$this->assertEquals('1200', $ticket->getPrice(), "Problème sur le calcul du bon prix.");
    }

    public function testSetCommand()
    {
    	$ticket = new Ticket();
    	$command = new Command();

    	$ticket->setCommand($command);

    	$this->assertNotNull($ticket->getCommand(), "Problème sur l'association du ticket à une commande.");
    }
}
