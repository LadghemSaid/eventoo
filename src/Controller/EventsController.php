<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Entity\Comment;
use App\Form\CommentType;

class EventsController extends AbstractController
{
    /**
     * @Route("/events", name="events.index")
     */
    public function index(EventRepository $repository)
    {
        $events = $repository->findAll();
        // $eventsFavorite= $repository->findBy(array('favorite'=>true));
        return $this->render('events/index.html.twig', [
            'current_menu' => 'events',
            'events' => $events
        ]);
    }

    /**
     * @Route("/event/{slug}", name="event.show" , requirements={"slug"="[a-z0-9\-]*"})
     */
    public function show(EventRepository $eventRepository, Event $event, CommentRepository $commentsRepository)
    {


        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('add.comment', array('id' => $event->getId())),
        ]);

        $comments = $commentsRepository->findArticleComment($event->getId(), 'DESC');

        $commentOptions = $event->getAllowComment();
        $allowComment = false;
        if (isset($commentOptions) && array_search('allowComment', $commentOptions) !== false) {
            $allowComment = true;

        }

        $event = $eventRepository->find($event);
        // $eventsFavorite= $repository->findBy(array('favorite'=>true));
        return $this->render('events/show.html.twig', [
            'current_menu' => 'events',
            'event' => $event,
            'formComment' => $formComment->createView(),
            'comments' => $comments,
            'allowComment' => $allowComment,
        ]);
    }
}
