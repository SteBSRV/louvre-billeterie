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
   * @ORM\Column(name="birth_date", type="date")
   * @Assert\DateTime()
   */
  protected $birthDate;

  /**
   * @ORM\Column(name="country", type="string")
   * @Assert\Type("string")
   */
  protected $country;

  /**
   * @ORM\Column(name="type", type="boolean")
   * @Assert\Type("bool")
   */
  protected $type;

  /**
   * @ORM\Column(name="reduced", type="boolean")
   * @Assert\Type("bool")
   */
  protected $reduced;

  /**
   * @ORM\Column(name="price", type="integer")
   * @Assert\Type("integer")
   */
  protected $price;

  /**
   * @ORM\ManyToOne(targetEntity="LA\TicketingBundle\Entity\Command", inversedBy="tickets", cascade={"persist"})
   * @ORM\JoinColumn(name="Command_id", referencedColumnName="id")
   * @Assert\Type("object")
   */
  protected $command;

  /**
   * @ORM\Column(name="visit_date", type="date")
   * @Assert\DateTime()
   * @Assert\GreaterThanOrEqual("today")
   */
  protected $visitDate;

  /**
   * @ORM\Column(name="validation_code", type="string", nullable=true)
   * @Assert\Type("string")
   */
  protected $validationCode;

  // Initialisation de la date de visite à aujourd'hui
  public function __construct()
  {
    $this->visitDate = new \DateTime();
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
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
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
     * Set type
     *
     * @param boolean $type
     *
     * @return Ticket
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return boolean
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return Ticket
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
     * Set validationCode
     *
     * @param string $validationCode
     *
     * @return Ticket
     */
    public function setValidationCode()
    {
        $validationCode = random_int(100000, 999999);
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
     * Set price
     *
     * @param \LA\TicketingBundle\Entity\Price $price
     *
     * @return Ticket
     */
    public function setPrice()
    {
        if ($this->reduced) {
            $this->price = $this::PRICE_REDUCED;

            return $this;
        }

        $now = new \DateTime('now');
        $age = $now->diff($this->birthDate)->y;

        if ($age < 4) {
            $this->price = $this::PRICE_FREE;
        } elseif ($age < 12) {
            $this->price = $this::PRICE_KID;
        } elseif ($age > 60) {
            $this->price = $this::PRICE_SENIOR;
        } else {
            $this->price = $this::PRICE_NORMAL;
        }

        return $this;
    }

    /**
     * Get price
     *
     * @return \LA\TicketingBundle\Entity\Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set command
     *
     * @param \LA\TicketingBundle\Entity\Command $command
     *
     * @return Ticket
     */
    public function setCommand(\LA\TicketingBundle\Entity\Command $command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return \LA\TicketingBundle\Entity\Command
     */
    public function getCommand()
    {
        return $this->command;
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
     *
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback
     */
    public function isTypeValid(ExecutionContext $context)
    {
        $message = "Vous ne pouvez plus réserver de billet Journée pour le jour même une fois 14h passé, vous pouvez cependant toujours réserver un billet Demi-journée";

        $now = new \DateTime('now');
        $today = $now->format('Y-m-d');
        $hour = $now->format('H');
        $visitDate = $this->visitDate->format('Y-m-d');

        if ($hour >= 14 && $this->type && ($visitDate == $today)) {
            $context->buildViolation($message)->atPath('type')->addViolation();
        }
    }
}
