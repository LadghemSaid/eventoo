<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Event;
use App\Entity\Visit;
use App\Entity\Ticket;
use App\Exception\InvalidVisitSessionException;
use App\Form\ContactType;
use App\Form\CustomerType;
use App\Form\TicketType;
use App\Form\VisitCustomerType;
use App\Form\VisitTicketsType;
use App\Form\VisitType;
use App\Manager\VisitManager;
use App\Repository\EventRepository;
use App\Service\EmailService;
use PHPUnit\Runner\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PublicHolidaysService;


class VisitController extends Controller
{


    /**
     * Page 2 - Initialisation de la visite - choix de la date / du type de billet / du nb de billets
     * @Route("/order/event/{event}", name="app_visit_order" , requirements={"slug"="[0-9\-]*"})
     * @param Request $request
     * @param VisitManager $visitManager
     * @param PublicHolidaysService $publicHolidaysService
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function orderAction(Request $request, VisitManager $visitManager, PublicHolidaysService $publicHolidaysService, $event, EventRepository $eventRepository)
    {

        //Récupération de l'event passer en get par id et vérification qu'il existe autrement retour a la home
        $event = $eventRepository->findBy(array('id' => $event));
        if (!$event) {
            return $this->redirectToRoute('index');
        }


        $visit = $visitManager->initVisit($event[0]);

        //$publicHolidays = $publicHolidaysService->getPublicHolidaysOnTheseTwoYears();

        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dates = explode('-', $form->getData()->getVisitDate());
            $startDate = new \DateTime(str_replace(' ', '', str_replace("/", "-", $dates[0])));
            $endDate = new \DateTime(str_replace(' ', '', str_replace("/", "-", $dates[1])));
            $visit->setVisitStartDate($startDate);
            $visit->setVisitEndDate($endDate);
            $visitManager->generateTickets($visit);

            return $this->redirect($this->generateUrl('app_visit_identify'));
        }

        return $this->render('Visit/order.html.twig', array(
            'form' => $form->createView(),
            'visit' => $visit,
            'bodyClass' => "reservationForm"
            //    'publicHolidays' => $publicHolidays
        ));

    }


    /**
     * page 3 - Identification des visiteurs - création des billets
     * @Route("/identification", name="app_visit_identify")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     */
    public function identifyAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_INIT);

        $form = $this->createForm(VisitTicketsType::class, $visit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visitManager->computePrice($visit);
            return $this->redirect($this->generateUrl('app_visit_customer'));

        }

        return $this->render('Visit/identify.html.twig', array(
            'form' => $form->createView(),
            'visit' => $visit,
            'bodyClass' => "reservationForm"

        ));
    }


    /**
     * page 4 - Coordonnées de l'acheteur - création du customer
     * @Route("/customer", name="app_visit_customer")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     *
     */
    public function customerAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_TICKET);

        $form = $this->createForm(VisitCustomerType::class, $visit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirect($this->generateUrl('app_visit_pay'));

        }

        return $this->render('Visit/customer.html.twig', array(
            'form' => $form->createView(),
            'visit' => $visit,
            'bodyClass' => "reservationForm"

        ));

    }

    /**
     * page 5 paiement
     * @Route("/pay", name="app_visit_pay")
     * @param Request $request
     * @param VisitManager $visitManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws InvalidVisitSessionException
     */
    public function payAction(Request $request, VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_CUSTOMER);

        if ($request->getMethod() === "POST") {
            //Création de la charge - Stripe
            $token = $request->request->get('stripeToken');

            // chargement de la clé secrète de Stripe
            $secretkey = $this->getParameter('stripe_secret_key');

            // paiement
            \Stripe\Stripe::setApiKey($secretkey);
            try {

                \Stripe\Charge::create(array(
                    "amount" => $visitManager->computePrice($visit) * 100,
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Réservation sur la billetterie Eventoo"));

                // Création du booking code
                $visitManager->generateBookingCodeWithEmail($visit);

                // enregistrement dans la base
                $em = $this->getDoctrine()->getManager();
                $em->persist($visit);
                $em->flush();
                $this->addFlash('notice', 'flash.payment.success');

                return $this->redirect($this->generateUrl('app_visit_confirmation'));

            } catch (\Exception $e) {
                $this->addFlash('danger', 'flash.payment.error');
            }


        }

        //Bypass du paiement
/*
        // Création du booking code
        $visitManager->generateBookingCodeWithEmail($visit);

        // enregistrement dans la base
        $em = $this->getDoctrine()->getManager();

        $em->persist($visit);
        $em->flush();
        $this->addFlash('notice', 'flash.payment.success');

        return $this->redirect($this->generateUrl('app_visit_confirmation'));

*/
        return $this->render('Visit/pay.html.twig', array(
            'visit' => $visit,
            'bodyClass' => "reservationForm"

        ));

    }

    /**
     * page 7 Bypass confirmation
     * @Route("/confirmationPassed", name="app_visit_confirmation_bypassed")
     * @throws InvalidVisitSessionException
     */
    public function confirmationActionByPassed(VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_CUSTOMER);

        // Création du booking code
        $visitManager->generateBookingCodeWithEmail($visit);

        // enregistrement dans la base
        $em = $this->getDoctrine()->getManager();

        $em->persist($visit);
        $em->flush();
        $this->addFlash('notice', 'flash.payment.success');

        return $this->redirect($this->generateUrl('app_visit_confirmation'));

    }



    /**
     * page 6 confirmation
     * @Route("/confirmation", name="app_visit_confirmation")
     * @throws InvalidVisitSessionException
     */
    public function confirmationAction(VisitManager $visitManager)
    {
        $visit = $visitManager->getCurrentVisit(Visit::IS_VALID_WITH_BOOKINGCODE);

        return $this->render('Visit/confirmation.html.twig', array(
            'visit' => $visit,
            'bodyClass' => "reservationForm"

        ));
    }


}
