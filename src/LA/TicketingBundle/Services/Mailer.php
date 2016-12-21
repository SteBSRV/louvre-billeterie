<?php
// src/LA/TicketingBundle/Services/Mailer.php

namespace LA\TicketingBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use LA\TicketingBundle\Entity\Order;

class Mailer
{
	protected $mailer;
	protected $templating;
	private $from = 'no-reply@steb-srv.fr';
	private $reply = 'contact@steb-srv.fr';
	private $name = 'Louvre Billeterie';

	public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
	}

	public function sendMessage($to, $subject, $body)
	{
		$mail = \Swift_Message::newInstance();

		$mail
			->setFrom(array($this->from => $this->name))
			->setTo($to)
			->setSubject($subject)
			->setBody($body)
			->setReplyTo(array($this->reply => $this->name))
			->setContentType('text/html')
		;

		$this->mailer->send($mail);
	}

	public function sendOrderSuccess(Order $order)
	{
		$subject = "[Louvre-Billeterie] Votre commande a été validée";
		$template = 'LATicketingBundle:Mail:order_success.html.twig';
		$to = $order->getMail();
		$body = $this->templating->render($template, array('order' => $order));
		$this->sendMessage($to, $subject, $body);
	}
}