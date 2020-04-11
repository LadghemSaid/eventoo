<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Assert\NotNull()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank(message="constraint.customer.notblank.lastname", groups={"customer_registration"})
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="constraint.customer.type.lastname",
     *     groups={"customer_registration"})
     *
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank(message="constraint.customer.notblank.firstname", groups={"customer_registration"})
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="constraint.ticket.type.firstname",
     *     groups={"identification_registration"})
     *
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="constraint.customer.notblank.email", groups={"customer_registration"})
     * @Assert\Email(
     *     strict=true,
     *     message="constraint.customer.message.email",
     *     groups={"customer_registration"})
     *
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="adress", type="string", length=255)
     * @Assert\NotBlank(message="constraint.customer.notblank.adress", groups={"customer_registration"})
     *
     */
    private $adress;

    /**
     * @var string
     * @ORM\Column(name="postCode", type="string", length=255)
     * @Assert\NotBlank(message="constraint.customer.notblank.postcode", groups={"customer_registration"})
     *
     */
    private $postCode;

    /**
     * @var string
     * @ORM\Column(name="city", type="string", length=255)
     * @Assert\NotBlank(message="constraint.customer.notblank.city", groups={"customer_registration"})
     *
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(groups={"customer_registration"})
     *
     */
    private $country;


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
     * @return Customer
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
     * @return Customer
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
     * Set email.
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set adress.
     *
     * @param string $adress
     *
     * @return Customer
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress.
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set postCode.
     *
     * @param string $postCode
     *
     * @return Customer
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode.
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return Customer
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Customer
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
}
