<?php

namespace Tests\LATicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketingControllerTest extends WebTestCase
{
    public function testCommandCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/command/create');

        $this->assertContains('Billeterie', $client->getResponse()->getContent(), "Erreur sur l'affichage de la billeterie.");
    }

    public function testCommandBuy()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/command/1/buy');

        $this->assertContains('Liste des billets', $client->getResponse()->getContent(), "Erreur sur l'affichage de la commande.");
    }

    public function testCommandConfirm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/command/1/confirm');

        $this->assertContains('Réservé par', $client->getResponse()->getContent(), "Erreur à l'affichage de la confirmation de la commande.");
    }

    public function testGeneratePdf()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ticket/1');

        $this->assertNotNull($client->getResponse()->getContent(), "Erreur lors de la génération des PDF.");
    }

    public function testStats()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Nombre de places vendues', $client->getResponse()->getContent(), "Erreur d'affichage des stats.");
    }

    public function testInfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/info');

        $this->assertContains('Tarifs', $client->getResponse()->getContent(), "Erreur sur l'affichage des tarifs.");
    }
}
