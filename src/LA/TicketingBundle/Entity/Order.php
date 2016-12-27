<?php
// src/LA/TicketingBundle/Entity/Order.php

namespace LA\TicketingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use LA\TicketingBundle\Validator\Constraints as LAAssert;

/**
 * @ORM\Table(name="la_order")
 * @ORM\Entity(repositoryClass="LA\TicketingBundle\Repository\OrderRepository")
 * @LAAssert\OrderCheck
 */
class Order
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(name="order_date", type="datetime")
   * @Assert\DateTime()
   */
  protected $orderDate;

  /**
   * @ORM\Column(name="mail", type="string", length=255, nullable=true)
   * @Assert\Email()
   */
  protected $mail;

  /**
   * @ORM\Column(name="visit_date", type="datetime")
   * @Assert\DateTime()
   * @Assert\GreaterThanOrEqual("today", message="Vous ne pouvez commander pour un jour passé.")
   */
  protected $visitDate;

  /**
   * @ORM\Column(name="tickets_type", type="string", columnDefinition="enum('journée', 'demi-journée')")
   * @Assert\Type("string")
   */
  protected $ticketsType;

  /**
   * @var ArrayCollection
   * @ORM\OneToMany(targetEntity="LA\TicketingBundle\Entity\Ticket", mappedBy="order", cascade={"persist"})
   * @Assert\Valid()
   */
  protected $tickets;

  /**
   * @ORM\Column(name="paid", type="boolean")
   * @Assert\Type("bool")
   */
  protected $paid = false;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->tickets = new ArrayCollection();
    $this->orderDate = new \DateTime('now');
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set orderDate
   *
   * @param \DateTime $orderDate
   */
  public function setOrderDate($orderDate)
  {
    $this->orderDate = $orderDate;
  }

  /**
   * Get orderDate
   *
   * @return \DateTime
   */
  public function getOrderDate()
  {
    return $this->orderDate;
  }

  /**
   * Set mail
   *
   * @param string $mail
   */
  public function setMail($mail)
  {
    $this->mail = $mail;;
  }

  /**
   * Get mail
   *
   * @return string
   */
  public function getMail()
  {
    return $this->mail;
  }

  /**
   * Set visitDate
   *
   * @param \DateTime $visitDate
   */
  public function setVisitDate($visitDate)
  {
    $this->visitDate = $visitDate;
  }

  /**
   * Get visitDate
   *
   * @return \DateTime
   */
  public function getVisitDate()
  {
    return $this->visitDate;
  }

  /**
   * Set ticketsType
   *
   * @param string $ticketsType
   */
  public function setTicketsType($ticketsType)
  {
    $this->ticketsType = $ticketsType;
  }

  /**
   * Get ticketsType
   *
   * @return string
   */
  public function getTicketsType()
  {
    return $this->ticketsType;
  }

  /**
   * Set paid
   *
   * @param boolean $paid
   */
  public function setPaid($paid)
  {
    $this->paid = $paid;
    
    foreach ($this->tickets as $ticket) {
      $ticket->generateValidationCode();
    }
  }

  /**
   * Get paid
   *
   * @return boolean
   */
  public function getPaid()
  {
    return $this->paid;
  }

  /**
   * Add ticket
   *
   * @param \LA\TicketingBundle\Entity\Ticket $ticket
   */
  public function addTicket(\LA\TicketingBundle\Entity\Ticket $ticket)
  {
    $this->tickets[] = $ticket;
  }

  /**
   * Remove ticket
   *
   * @param \LA\TicketingBundle\Entity\Ticket $ticket
   */
  public function removeTicket(\LA\TicketingBundle\Entity\Ticket $ticket)
  {
    $this->tickets->removeElement($ticket);
  }

  /**
   * Get tickets
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getTickets()
  {
    return $this->tickets;
  }

  // Custom functions :

  /**
   * Get nbTickets
   *
   * @return integer
   */
  public function getNbTickets()
  {
    return count($this->tickets);
  }

  /**
   * Get TotalAmount
   *
   * @return integer
   */
  public function getTotalAmount()
  {
    $amount = 0;

    foreach ($this->tickets as $ticket) {
      $amount += $ticket->getPrice();
    }

    return $amount;
  }

  /**
   * Set TicketsOrder
   */
  public function setTicketsOrder()
  {
    foreach ($this->tickets as $ticket) {
      $ticket->setOrder($this);
    }
  }
}