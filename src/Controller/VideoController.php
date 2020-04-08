<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @Route("/video", name="video.index")
     */
    public function index(VideoRepository $repository)
    {
        $videos = $repository->findAll();
        return $this->render('video/index.html.twig', [
            'current_menu' => 'video',
            'videos' => $videos
        ]);
    }
}
