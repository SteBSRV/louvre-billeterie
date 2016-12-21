<?php
// src/LA/TicketingBundle/Entity/Ticket.php

namespace LA\TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * @ORM\Table(name="la_ticket")
 * @ORM\Entity(repositoryClass="LA\TicketingBundle\Repository\TicketRepository")
 */
class Ticket
{
  const PRICE_FREE    = 0;
  const PRICE_KID     = 800;
  const PRICE_NORMAL  = 1600;
  const PRICE_SENIOR  = 1200;
  const PRICE_REDUCED = 1000;

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(name="name", type="string", length=255)
   * @Assert\NotBlank()
   */
  protected $name;

  /**
   * @ORM\Column(name="first_name", type="string", length=255)
   * @Assert\NotBlank()
   */
  protected $firstName;

  /**
   * @ORM\Column(name="country", type="string")
   * @Assert\Type("string")
   */
  protected $country;

  /**
   * @ORM\Column(name="birth_date", type="date")
   * @Assert\DateTime()
   */
  protected $birthDate;

  /**
   * @ORM\Column(name="reduced", type="boolean")
   * @Assert\Type("bool")
   */
  protected $reduced = false;

  /**
   * @ORM\Column(name="validation_code", type="string", nullable=true)
   * @Assert\Type("string")
   */
  protected $validationCode;

  /**
   * @ORM\ManyToOne(targetEntity="LA\TicketingBundle\Entity\Order", inversedBy="tickets", cascade={"persist"})
   * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
   * @Assert\Type("object")
   */
  protected $order;

  /**
   * @ORM\Column(name="used", type="boolean", nullable=true)
   * @Assert\Type("bool")
   */
  protected $used = false;

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
   * @return Ticket
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
   * @return Ticket
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
   * Set country
   *
   * @param string $country
   *
   * @return Ticket
   */
  public function setCountry($country)
  {
    $this->country = $country;

    return $this;
  }

  /**
   * Get country
   *
   * @return string
   */
  public function getCountry()
  {
    return $this->country;
  }

  /**
   * Set birthDate
   *
   * @param \DateTime $birthDate
   *
   * @return Ticket
   */
  public function setBirthDate(\DateTime $birthDate)
  {
    $this->birthDate = $birthDate;

    return $this;
  }

  /**
   * Get birthDate
   *
   * @return \DateTime
   */
  public function getBirthDate()
  {
    return $this->birthDate;
  }

  /**
   * Set reduced
   *
   * @param boolean $reduced
   *
   * @return Ticket
   */
  public function setReduced($reduced)
  {
    $this->reduced = $reduced;

    return $this;
  }

  /**
   * Get reduced
   *
   * @return boolean
   */
  public function getReduced()
  {
    return $this->reduced;
  }

  /**
   * Set validationCode
   *
   * @param string $validationCode
   *
   * @return Ticket
   */
  public function setValidationCode($validationCode)
  {
    $this->validationCode = $validationCode;

    return $this;
  }

  /**
   * Get validationCode
   *
   * @return string
   */
  public function getValidationCode()
  {
    return $this->validationCode;
  }

  /**
   * Generate validationCode
   *
   * @return Ticket
   */
  public function generateValidationCode()
  {
    $code = substr(md5(random_int(100000, 999999)), 0, 10);
    $this->validationCode = $code;

    return $this;
  }

  /**
   * Set used
   *
   * @param boolean $used
   *
   * @return Ticket
   */
  public function setUsed($used)
  {
    $this->used = $used;

    return $this;
  }

  /**
   * Get used
   *
   * @return boolean
   */
  public function getUsed()
  {
    return $this->used;
  }

  /**
   * Set order
   *
   * @param \LA\TicketingBundle\Entity\Order $order
   *
   * @return Ticket
   */
  public function setOrder(\LA\TicketingBundle\Entity\Order $order = null)
  {
    $this->order = $order;

    return $this;
  }

  /**
   * Get order
   *
   * @return \LA\TicketingBundle\Entity\Order
   */
  public function getOrder()
  {
    return $this->order;
  }

  // Custom functions :

  /**
   * Get price
   *
   * @return integer
   */
  public function getPrice()
  {
    if ($this->reduced) {
      $price = self::PRICE_REDUCED;

      return $price;
    }

    $now = new \DateTime('now');
    $age = $now->diff($this->birthDate)->y;

    if ($age < 4) {
      $price = self::PRICE_FREE;
    } elseif ($age < 12) {
      $price = self::PRICE_KID;
    } elseif ($age > 60) {
      $price = self::PRICE_SENIOR;
    } else {
      $price = self::PRICE_NORMAL;
    }

    return $price;
  }
}