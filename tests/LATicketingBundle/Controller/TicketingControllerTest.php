<?php

namespace Tests\LATicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketingControllerTest extends WebTestCase
{
    public function testCommandCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/command/create');

        $this->assertContains('Billeterie', $client->getResponse()->getContent());
    }

    public function testCommandBuy()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/command/1/buy');

        $this->assertContains('Liste des billets', $client->getResponse()->getContent());
    }

    public function testCommandConfirm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/command/1/confirm');

        $this->assertContains('Réservé par', $client->getResponse()->getContent());
    }

    public function testGeneratePdf()
    {
        $client = static::createClient();

        // $crawler = $client->request('GET', '/ticket/1');

        // $this->assertContains('Billeterie', $client->getResponse()->getContent());
    }

    public function testStats()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Nombre de places vendues', $client->getResponse()->getContent());
    }

    public function testInfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/info');

        $this->assertContains('Tarifs', $client->getResponse()->getContent());
    }
}
