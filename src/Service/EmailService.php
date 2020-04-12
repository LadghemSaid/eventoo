<?php

namespace App\Service;

use App\Entity\Visit;
use App\Form\ContactType;
use Endroid\QrCode\Factory\QrCodeFactory;
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
    private $qrCodeFactory;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, $emailfrom, TranslatorInterface $translator,QrCodeFactory $qrCodeFactory)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->emailfrom = $emailfrom;
        $this->translator = $translator;
        $this->qrCodeFactory = $qrCodeFactory;
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
        //$qrCode = $this->qrCodeFactory->create($visit->getBookingCode(), [ 'size' => 150,]);
        $message = (new \Swift_Message())
            ->setContentType('text/html')
            ->setSubject($this->translator->trans('emailservice.subject_validator_order'))
            ->setFrom($this->emailfrom) // je récupère l'adresse que j'ai enregistré dans parameters.yml grâce à cet argument
            ->setTo($email);

        $img = $message->embed(\Swift_Image::fromPath('img/icon.png')); // j'ajoute l'image que je souhaite afficher
        //$qrCode = $message->embed(\Swift_Image::fromPath()); // j'ajoute l'image que je souhaite afficher

        $message->setBody($this->templating->render('Emails/registration.html.twig', ['visit' => $visit, 'img' => $img]));
        /* dans les variables à afficher, en plus de la visite, j'affiche l'image que j'appelerai dans Twig en {{ img }}*/

        return $this->mailer->send($message);

    }

}
