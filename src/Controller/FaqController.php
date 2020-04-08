<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq.index")
     */
    public function index(FaqRepository $repository)
    {

        $faqQuestions = $repository->findAll();
        return $this->render('faq/index.html.twig', [
            'current_menu' => 'faq',
            'faqQuestions' => $faqQuestions
        ]);
    }
}
