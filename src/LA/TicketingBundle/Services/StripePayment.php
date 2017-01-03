<?php
// src/LA/TicketingBundle/Services/StripePayment.php

namespace LA\TicketingBundle\Services;

use LA\TicketingBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class StripePayment
{
	/**
	 * @var EntityManager
	 */
	protected $em;
	
	public function __construct($stripe_key, EntityManager $em)
	{
		\Stripe\Stripe::setApiKey($stripe_key);
		$this->em = $em;
	}

	public function sendPayment(Order $order, Request $request)
	{
        $order->setMail($request->get('stripeEmail'));

        try {
            \Stripe\Charge::create(array(
                'amount'      => $order->getTotalAmount(),
                'currency'    => 'eur',
                'source'      => $request->get('stripeToken'),
                'description' => 'Paiement des billets',
            ));
        } catch(\Stripe\Error\Card $e) {
            return $error = true;
		}

		$error = false;

		$order->setPaid(true);
        $this->em->persist($order);
        $this->em->flush();
		
		return $error;
	}
}
