<?php

namespace App\Service;

use App\Entity\Visit;
use App\Form\ContactType;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class EmailService
 * @package App\Service
 */
class EmailService
{
    // Ce service prend trois arguments : l'envoi de mail, le template, et l'adresse d'envoi
    // Ces trois arguments sont repris dans services.yml
    // Dans services.yml, j'indique quelle adresse mail il y a derrière $emailfrom

    protected $mailer;
    protected $templating;
    private $emailfrom;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, $emailfrom, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->emailfrom = $emailfrom;
        $this->translator = $translator;
    }

    /**
     * @param Visit $visit
     * @return int
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMailConfirmation(Visit $visit)
    {
        $email = $visit->getCustomer()->getEmail();

        $message = (new \Swift_Message())
            ->setContentType('text/html')
            ->setSubject($this->translator->trans('emailservice.subject_validator_order'))
            ->setFrom($this->emailfrom) // je récupère l'adresse que j'ai enregistré dans parameters.yml grâce à cet argument
            ->setTo($email);

        $img = $message->embed(\Swift_Image::fromPath('assets/img/logo-louvre.jpg')); // j'ajoute l'image que je souhaite afficher

        $message->setBody($this->templating->render('Emails/registration.html.twig', ['visit' => $visit, 'img' => $img]));
        /* dans les variables à afficher, en plus de la visite, j'affiche l'image que j'appelerai dans Twig en {{ img }}*/

        return $this->mailer->send($message);

    }


    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMailContact($data)
    {
        $message = (new \Swift_Message($this->translator->trans('email.subject_contact_page')))
            ->setFrom($data['email']) // je récupère l'adresse donnée par l'internaute dans le formulaire.
            // Dans le controller, j'ai appelé les datas par  $emailService->sendMailContact($form->getData());
            ->setTo($this->emailfrom) // je récupère l'adresse que j'ai enregistré dans parameters.yml grâce à cet argument
            ->setBody($this->templating->render('Emails/contact.html.twig',
                array('data' => $data)))
            ->setContentType('text/html');

        $this->mailer->send($message);
    }
}
