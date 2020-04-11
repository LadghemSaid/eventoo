<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    const FULL_DAY_PRICE = 16;
    const FULL_DAY_DISCOUNT = 10;
    const FULL_DAY_SENIOR = 12;
    const FULL_DAY_CHILD = 8;
    const FREE_TICKET = 0;

    const HALF_DAY_PRICE = 8;
    const HALF_DAY_DISCOUNT = 5;
    const HALF_DAY_SENIOR = 6;
    const HALF_DAY_CHILD = 4;

    const MIN_AGE_CHILD = 4;
    const MAX_AGE_CHILD = 12;
    const MIN_AGE_SENIOR = 60;

    /*
     * Exemples
     * Comment mieux nommer les constantes
     *
    const AGE_CHILD = 4;
    const AGE_ADULT = 12;
    const AGE_SENIOR = 60;
    const PRICE_ADULT = 16;
    const PRICE_DISCOUNT = 10;
    const PRICE_SENIOR = 12;
    const PRICE_CHILD = 8;
    const PRICE_FREE = 0;
    const PRICE_HALF_DAY_COEFF = 0.5;
    */


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Assert\NotNull()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank(message="constraint.ticket.notblank.lastname", groups={"identification_registration"})
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="constraint.ticket.type.lastname",
     *     groups={"identification_registration"})
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank(message="constraint.ticket.notblank.firstname", groups={"identification_registration"})
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="constraint.ticket.type.firstname",
     *     groups={"identification_registration"})
     *
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(groups={"identification_registration"})
     *
     */
    private $country;

    /**
     * @var \DateTime
     * @ORM\Column(name="birthday", type="date")
     * @Assert\LessThan("today", groups={"identification_registration"})
     * @Assert\Date(groups={"identification_registration"})
     *
     */
    private $birthday;

    /**
     * @var bool
     * @ORM\Column(name="discount", type="boolean")
     */
    private $discount;

    /**
     * @var int
     * @ORM\Column(name="price", type="integer")
     */
    private $price;


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Visit", inversedBy="tickets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid(groups={"identification_registration"})
     */
    private $visit;


    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        $this->birthday = (new \Datetime('2000-01-01'));
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return Ticket
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return Ticket
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set country.
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
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set birthday.
     *
     * @param \DateTime $birthday
     *
     * @return Ticket
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday.
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set discount.
     *
     * @param bool $discount
     *
     * @return Ticket
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount.
     *
     * @return bool
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set price.
     *
     * @param int $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set visit.
     *
     * @param \App\Entity\Visit $visit
     *
     * @return Ticket
     */
    public function setVisit(\App\Entity\Visit $visit)
    {
        $this->visit = $visit;

        return $this;
    }

    /**
     * Get visit.
     *
     * @return \App\Entity\Visit
     */
    public function getVisit()
    {
        return $this->visit;
    }
}
