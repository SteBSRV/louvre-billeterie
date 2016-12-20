<?php
// src/LA/TicketingBundle/Entity/Command.php

namespace LA\TicketingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="la_command")
 * @ORM\Entity(repositoryClass="LA\TicketingBundle\Repository\CommandRepository")
 */
class Command
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(name="name", type="string", length=255)
   * @Assert\NotBlank(message="Veuillez entrer un nom.")
   */
  protected $name;

  /**
   * @ORM\Column(name="first_name", type="string", length=255)
   * @Assert\NotBlank(message="Veuillez entrer un prénom.")
   */
  protected $firstName;

  /**
   * @ORM\Column(name="mail", type="string", length=255)
   * @Assert\NotBlank(message="Veuillez entrer un prénom.")
   * @Assert\Email(message="Veuillez saisir une adresse mail valide.")
   */
  protected $mail;

  /**
   * @ORM\Column(name="nb_tickets", type="integer", nullable=true)
   * @Assert\Type("integer")
   */
  protected $nbTickets;

  /**
   * @
   * @ORM\Column(name="amount", type="integer", nullable=true)
   * @Assert\Type("integer")
   */
  protected $amount;

  /**
   * @ORM\Column(name="date", type="datetime")
   * @Assert\DateTime()
   */
  protected $date;

  /**
   * @ORM\Column(name="valid", type="boolean", nullable=true)
   * @Assert\Type("bool")
   */
  protected $valid = false;

  /**
   * @var ArrayCollection
   * @ORM\OneToMany(targetEntity="LA\TicketingBundle\Entity\Ticket", mappedBy="command", cascade={"persist"})
   * @Assert\NotBlank()
   * @Assert\Valid()
   */
  protected $tickets;

  /**
   * Constructor
   */
  public function __construct()
  {
      $this->tickets = new ArrayCollection();
      $this->date = new \DateTime('now');
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
   * Set name
   *
   * @param string $name
   *
   * @return Command
   */
  public function setName($name)
  {
      $this->name = $name;

      return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName()
  {
      return $this->name;
  }

  /**
   * Set firstName
   *
   * @param string $firstName
   *
   * @return Command
   */
  public function setFirstName($firstName)
  {
      $this->firstName = $firstName;

      return $this;
  }

  /**
   * Get firstName
   *
   * @return string
   */
  public function getFirstName()
  {
      return $this->firstName;
  }

  /**
   * Set mail
   *
   * @param string $mail
   *
   * @return Command
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
   * Set nbTickets
   *
   * @param integer $nbTickets
   *
   * @return Command
   */
  public function setNbTickets()
  {
      $nbTickets = count($this->tickets);

      $this->nbTickets = $nbTickets;// = count Tickets;

      return $this;
  }

  /**
   * Get nbTickets
   *
   * @return integer
   */
  public function getNbTickets()
  {
      return $this->nbTickets;
  }

  /**
   * Set amount
   *
   * @param integer $amount
   *
   * @return Command
   */
  public function setAmount($amount)
  {
      $this->amount = $amount;

      return $this;
  }

  /**
   * Get amount
   *
   * @return integer
   */
  public function getAmount()
  {
      return $this->amount;
  }

  /**
   * Set date
   *
   * @param string $date
   *
   * @return Command
   */
  public function setDate($date)
  {
      $this->date = $date;

      return $this;
  }

  /**
   * Get date
   *
   * @return string
   */
  public function getDate()
  {
      return $this->date;
  }

  /**
   * Set valid
   *
   * @param boolean $valid
   *
   * @return Command
   */
  public function setValid($valid)
  {
      $this->valid = $valid;

      return $this;
  }

  /**
   * Get valid
   *
   * @return boolean
   */
  public function getValid()
  {
      return $this->valid;
  }

  /**
   * Add ticket
   *
   * @param Ticket $ticket
   *
   * @return Command
   */
  public function addTicket(Ticket $ticket)
  {
      $this->tickets[] = $ticket;

      $ticket->setCommand($this);

      return $this;
  }

  /**
   * Remove ticket
   *
   * @param Ticket $ticket
   *
   * @return Command
   */
  public function removeTicket(Ticket $ticket)
  {
      $this->tickets->removeElement($ticket);

      return $this;
  }

  /**
   * Get tickets
   *
   * @return ArrayCollection
   */
  public function getTickets()
  {
      return $this->tickets;
  }
}
