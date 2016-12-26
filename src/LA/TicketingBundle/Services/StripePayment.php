<?php
// src/LA/TicketingBundle/Services/StripePayment.php

namespace LA\TicketingBundle\Services;

use LA\TicketingBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class StripePayment
{
	protected $order;
	protected $token;
	protected $em;
	
	public function __construct($stripe_key, EntityManager $em)
	{
		\Stripe\Stripe::setApiKey($stripe_key);// A completer avec parameters
		$this->em = $em;
	}

	public function sendPayment(Order $order, Request $request)
	{
        $order->setMail($request->get('stripeEmail'));
        $this->token = $request->get('stripeToken');

        try {
            \Stripe\Charge::create(array(
                'amount'      => $order->getTotalAmount(),
                'currency'    => 'eur',
                'source'      => $this->token,
                'description' => 'Paiement des billets',
                )
            );
        } catch(\Stripe\Error\Card $e) {
        	$error = true;
        	
            return $error;
		}

		$error = false;

		$order->setPaid(true);
        $this->em->persist($order);
        $this->em->flush();
		
		return $error;
	}
}