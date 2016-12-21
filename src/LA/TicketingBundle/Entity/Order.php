<?php
// src/LA/TicketingBundle/Entity/Order.php

namespace LA\TicketingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="la_order")
 * @ORM\Entity(repositoryClass="LA\TicketingBundle\Repository\OrderRepository")
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
   */
  protected $mail;

  /**
   * @ORM\Column(name="visit_date", type="datetime")
   * @Assert\DateTime()
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
   * @Assert\NotBlank()
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
   * Set mail
   *
   * @param string $mail
   *
   * @return Order
   */
  public function setMail($mail)
  {
    $this->mail = $mail;

    return $this;
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
   *
   * @return Order
   */
  public function setVisitDate($visitDate)
  {
    $this->visitDate = $visitDate;

    return $this;
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
   *
   * @return Order
   */
  public function setTicketsType($ticketsType)
  {
    $this->ticketsType = $ticketsType;

    return $this;
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
   *
   * @return Order
   */
  public function setPaid()
  {
    $this->paid = true;
    
    foreach ($this->tickets as $ticket) {
      $ticket->generateValidationCode();
    }

    return $this;
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
   *
   * @return ORder
   */
  public function addTicket(\LA\TicketingBundle\Entity\Ticket $ticket)
  {
    $this->tickets[] = $ticket;

    return $this;
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
   *
   * @return Order
   */
  public function setTicketsOrder()
  {
    foreach ($this->tickets as $ticket) {
      $ticket->setOrder($this);
    }

    return $this;
  }
}
