<?php

namespace Tests\LATicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketingControllerTest extends WebTestCase
{
    public function testInfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/info');

        $this->assertContains('Mardi    : Fermé', $client->getResponse()->getContent());
        $this->assertContains('Prix : 10€', $client->getResponse()->getContent());
    }

    public function testOrderCreate()
    {
    	$client = static::createClient();
    	$client->followRedirects(true);
    	$crawler = $client->request('GET', '/order/create');

    	$form = $crawler->selectButton('Valider')->form();

		$values = array(
			'la_ticketingbundle_order' => array(
				'_token'      => $form['la_ticketingbundle_order[_token]']->getValue(),
				'visitDate'   => array(
					'day'   => 24,
					'month' => 03,
					'year'  => 2017,
				),
				'ticketsType' => 'journée',
				'nbTickets'   => 1,
				'tickets'     => array(
					'Ticket-1' => array(
						'name'      => 'Ebizet',
						'firstName' => 'Steve',
						'country'   => 'FR',
						'birthDate' => array(
							'day'    => 24,
							'month'  => 03,
							'year'   => 1990,
						),
					),
				),
			)

		);

		$client->request($form->getMethod(), $form->getUri(), $values);

    	$this->assertContains('Commande valide.', $client->getResponse()->getContent());
    }
}
