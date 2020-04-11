<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as LouvreAssert;

/**
 * Visit
 *
 * @ORM\Table(name="visit")
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 * @UniqueEntity("bookingCode")
 * @LouvreAssert\LimitedReservationAfterHour(hour=14,groups={"order_registration"})
 * @LouvreAssert\OneThousandTickets(nbTicketsByDay=Visit::NB_TICKET_MAX_DAY, groups={"order_registration"})
 *
 *
 */
class Visit
{

    const TYPE_HALF_DAY = 0;
    const TYPE_FULL_DAY = 1;
    const NB_TICKET_MAX_DAY = 1000;

    const LIMITED_HOUR_TODAY = 16;



    const IS_VALID_INIT = ["order_registration"];
    const IS_VALID_WITH_TICKET = ["order_registration", "identification_registration"];
    const IS_VALID_WITH_CUSTOMER = ["order_registration", "identification_registration", "customer_registration"];
    const IS_VALID_WITH_BOOKINGCODE = ["order_registration", "identification_registration", "customer_registration", "pay_registration"];

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
     * @var \DateTime
     * @ORM\Column(name="invoiceDate", type="datetime")
     * @Assert\DateTime(groups={"order_registration"})
     */
    private $invoiceDate;

    /**
     *
     * @var \DateTime
     * @ORM\Column(name="visitDate", type="date")
     * @LouvreAssert\ToLateForToday(hour=16, groups={"order_registration"})
     * @LouvreAssert\NoReservationOnTuesday(day=2, groups={"order_registration"})
     * @LouvreAssert\NoReservationOnSunday(day=0, groups={"order_registration"})
     * @LouvreAssert\NoReservationOnPublicHolidays(publicHolidays="",groups={"order_registration"})
     * @Assert\Range(min="today", minMessage="constraint.visit.min.visitdate",
     *     max="+1 year", maxMessage="constraint.visit.max.visitdate", groups={"order_registration"})
     * @Assert\NotNull()
     */
    private $visitDate;

    /**
     * @var integer
     * @ORM\Column(name="type", type="integer")
     * @Assert\NotBlank(message="constraint.visit.type", groups={"order_registration"})
     *
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(name="nbTicket", type="integer")
     * @Assert\Range(min=1, minMessage="constraint.visit.min.nb.tickets", max=20,
     *     maxMessage="constraint.visit.max.nb.tickets", groups={"order_registration"})
     */
    private $nbTicket;

    /**
     * @var int
     * @ORM\Column(name="totalAmount", type="integer")
     *
     */
    private $totalAmount;

    /**
     * @var string
     * @ORM\Column(name="bookingCode", type="string", unique=true)
     * @Assert\NotNull(groups={"pay_registration"})
     *
     */
    private $bookingCode;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid(groups={"customer_registration"})
     */
    private $customer;


    /**
     * @var Ticket[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="visit", cascade={"persist"})
     * @Assert\Valid(groups={"identification_registration"})
     */
    private $tickets;


    /**
     * Visit constructor.
     *
     */
    public function __construct()
    {
        $this->setInvoiceDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $this->tickets = new ArrayCollection();
        //$this->visitDate = (new \DateTime())/*->modify('+1 day')*/;

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
     * Set invoiceDate.
     *
     * @param \DateTime $invoiceDate
     *
     * @return Visit
     */
    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    /**
     * Get invoiceDate.
     *
     * @return \DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Set visitDate.
     *
     * @param \DateTime $visitDate
     *
     * @return Visit
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate.
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Visit
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nbTicket.
     *
     * @param int $nbTicket
     *
     * @return Visit
     */
    public function setNbTicket($nbTicket)
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    /**
     * Get nbTicket.
     *
     * @return int
     */
    public function getNbTicket()
    {
        return $this->nbTicket;
    }

    /**
     * Set totalAmount.
     *
     * @param int $totalAmount
     *
     * @return Visit
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount.
     *
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set bookingCode.
     *
     * @param int $bookingCode
     *
     * @return Visit
     */
    public function setBookingCode($bookingCode)
    {
        $this->bookingCode = $bookingCode;

        return $this;
    }

    /**
     * Get bookingCode.
     *
     * @return int
     */
    public function getBookingCode()
    {
        return $this->bookingCode;
    }

    /**
     * Set customer.
     *
     * @param \App\Entity\Customer $customer
     *
     * @return Visit
     */
    public function setCustomer(\App\Entity\Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get customer.
     *
     * @return \App\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add ticket.
     *
     * @param \App\Entity\Ticket $ticket
     *
     * @return Visit
     */
    public function addTicket(\App\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setVisit($this);

        return $this;
    }

    /**
     * Remove ticket.
     *
     * @param \App\Entity\Ticket $ticket
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTicket(\App\Entity\Ticket $ticket)
    {
        return $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets.
     *
     * @return Ticket[]|\Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
