<?php

namespace App\Manager;


use App\Entity\Ticket;
use App\Entity\Visit;
use App\Exception\InvalidVisitSessionException;
use App\Service\EmailService;
use App\Service\PublicHolidaysService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as Embedded;
use Stripe\ApiOperations\Request;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



/**
 * Class VisitManager
 * @package App\Manager
 * @Embedded\Embedded
 */
class VisitManager
{
    const SESSION_ID_CURRENT_VISIT = "visit";

    /**
     * @var SessionInterface
     */
    private $session;
    private $publicHolidaysService;
    private $validator;
    private $emailService;



    public function __construct(SessionInterface $session, PublicHolidaysService $publicHolidaysService, ValidatorInterface $validator,
                                EmailService $emailService)
    {
        $this->session = $session;
        $this->publicHolidaysService = $publicHolidaysService;
        $this->validator = $validator;
        $this->emailService = $emailService;
    }


    /**
     * Page 2 - Order
     * Initialisation de la visite et de la session
     * Création de l'objet Visit
     * @return Visit
     */
    public function initVisit($event)
    {
        $visit = new Visit();
        $visit->setEvent($event);
        $visit->setVisitDate($visit->getEvent()->getStartDate());
        //$this->whichVisitDay($visit);
        $this->session->set(self::SESSION_ID_CURRENT_VISIT,$visit);

        return $visit;
    }


    /**
     *
     * Retourne la visite en cours dans la session
     * @param array $group
     * @return Visit
     * @throws InvalidVisitSessionException
     * $validateBy sert à appeler la constante de classe visit
     */
    public function getCurrentVisit($validateBy = null)
    {
        $visit = $this->session->get(self::SESSION_ID_CURRENT_VISIT);

        if(!$visit instanceof Visit)
        {
            throw new InvalidVisitSessionException("Cette page est inaccessible.");

        }

        if(!empty($validateBy) && count($this->validator->validate($visit,null,$validateBy)) > 0)
        {
            throw new InvalidVisitSessionException("Commande invalide.");
        }

        return $visit;
    }


    /**
     * Page 2 - Order
     * Retourne le nombre de tickets en fonction du $nbticket demandé
     * @param Visit $visit
     */
    public function generateTickets(Visit $visit)
    {
        for($i= 1; $i<=$visit->getNbTicket(); $i++)
        {
            $visit->addTicket(new Ticket());
        }

        return $visit;
    }


    /**
     * Page 2 - Order
     * Affichage dans le datepicker sur la page Order
     * @param Visit $visit
     * @param PublicHolidaysService $publicHolidaysService
     */
    public function whichVisitDay(Visit $visit)
    {
        date_default_timezone_set('Europe/Paris');
        $hour = date("H");
        $today = date("w");
        $tomorrow = date('w', strtotime('+1 day'));
        $publicHolidays = $this->publicHolidaysService->getPublicHolidaysOnTheseTwoYears();


        if($hour > Visit::LIMITED_HOUR_TODAY || $today == 0 || $today == 2 || $today == $publicHolidays) {
            $visitDate = (new \DateTime())->modify('+ 1 days');

            if($tomorrow  == 0 || $tomorrow == 2 || $tomorrow == $publicHolidays) {
                $visitDate = (new \DateTime())->modify('+ 2 days');
            }
        }
        else {
            $visitDate = (new \DateTime());
        }

        $visit->setVisitDate($visitDate);
        return $visitDate;
    }



    /**
     * Page 3 - Identification des visiteurs
     * Calcule le prix de chaque billet en fonction de l'âge et du type de billet
     * @param Visit $visit
     * @param Ticket $ticket
     * @return int
     */
    public function computeTicketPrice(Ticket $ticket)
    {
        $birthday = $ticket->getBirthday();
        $visit = $ticket->getVisit();
        $today = new \DateTime();
        $age = date_diff($birthday, $today)->y;

        $discount = $ticket->getDiscount();

        /*
         * Exemple
         * Comment mieux trouver le bon prix en fonction de l'âge
         *
         *
         *
        //Trouver le bon tarif
        if($age < Ticket::AGE_CHILD){ // Bébé
            $price = Ticket::PRICE_FREE;
        }elseif($age < Ticket::AGE_ADULT){ //Enfant
            $price = Ticket::PRICE_CHILD;
        }elseif ($age < Ticket::AGE_SENIOR){ // Adulte/Normal
            $price = Ticket::PRICE_ADULT;
        }else{ // Senior
            $price = Ticket::PRICE_SENIOR;
        }
        // Verifier reduction
        if($ticket->getDiscount() === true && $price > Ticket::PRICE_DISCOUNT ){
            $price = Ticket::PRICE_DISCOUNT;
        }
        //Appliquer coeff journée/demi journée
        if($ticket->getVisit()->getType() === Visit::TYPE_HALF_DAY){
            $price = $price * Ticket::HALF_DAY_COEFF;
        }
        */



        if ($visit->getType() == Visit::TYPE_FULL_DAY)
        {
            if ($age >= Ticket::MAX_AGE_CHILD && $age < Ticket::MIN_AGE_SENIOR) {
                $price = Ticket::FULL_DAY_PRICE;

                if ($age >= Ticket::MAX_AGE_CHILD && $age < Ticket::MIN_AGE_SENIOR && $discount == true){
                    $price = Ticket::FULL_DAY_DISCOUNT;
                }

            } elseif ($age >= Ticket::MIN_AGE_SENIOR) {
                $price = Ticket::FULL_DAY_SENIOR;
            } elseif ($age >= Ticket::MIN_AGE_CHILD && $age < Ticket::MAX_AGE_CHILD) {
                $price = Ticket::FULL_DAY_CHILD;
            } else {
                $price = Ticket::FREE_TICKET;
            }
        }

        elseif ($visit->getType() == Visit::TYPE_HALF_DAY)
        {
            if ($age >= Ticket::MAX_AGE_CHILD && $age < Ticket::MIN_AGE_SENIOR) {
                $price = Ticket::HALF_DAY_PRICE;

                if ($age >= Ticket::MAX_AGE_CHILD && $age < Ticket::MIN_AGE_SENIOR && $discount == true){
                    $price = Ticket::HALF_DAY_DISCOUNT;
                }

            } elseif ($age >= Ticket::MIN_AGE_SENIOR) {
                $price = Ticket::HALF_DAY_SENIOR;
            } elseif ($age >= Ticket::MIN_AGE_CHILD && $age < Ticket::MAX_AGE_CHILD) {
                $price = Ticket::HALF_DAY_CHILD;
            } else {
                $price = Ticket::FREE_TICKET;
            }
        }

        $ticket->setPrice($price);
        return $price;
    }


    /**
     * Page 3 - Identification
     * Calcule le prix total de la visite
     * @param Visit $visit
     */
    public function computePrice(Visit $visit)
    {
        $totalAmount = 0;

        foreach ($visit->getTickets() as $ticket) {
            $priceTicket = $this->computeTicketPrice($ticket);
            $totalAmount += $priceTicket;
        }

        $visit->setTotalAmount($totalAmount);
        return $totalAmount;

    }


    /**
     * Page 5 - Pay
     * Génère le bookingCode
     * @param Visit $visit
     */
    public function generateBookingCodeWithEmail(Visit $visit)
    {
        $bookingCode = uniqid();
        $visit->setBookingCode($bookingCode);

        $this->emailService->sendMailConfirmation($visit);
        return $visit;

    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function generateMailContact()
    {
        $this->emailService->sendMailContact();
    }

}
