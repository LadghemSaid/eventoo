<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\EventRepository;
use App\Repository\JobRepository;
use App\Repository\ArticleRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommentsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    /**
     * @Route("/add/comment/{id}", name="add.comment", methods={"POST"})
     * @param Request $req
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function add(Request $req,$id, ArticleRepository $articleRepo,EventRepository $eventRepository)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $referer = explode('/', $req->headers->get('referer'));
            $com = $form->getData();

             if ($referer[4] === "article" ) {

                 $post = $articleRepo->find($id);
                $commentValidatingAuto = false;
                if (array_search('commentValidatingAuto', $post->getAllowComment())) {
                    $commentValidatingAuto = true;
                }


                $com->setArticle($post)
                    ->setCreatedAt(new \DateTime())
                    ->setApproved($commentValidatingAuto);
                $this->em->persist($com);
                $this->em->flush();


                $this->addFlash('success',"Commentaire ajouté avec succés :)");

                return $this->redirectToRoute('article.show', array('slug' => $post->getSlug()));

            }else  if ($referer[4] === "event" ) {
                 $post = $eventRepository->findBy(['id'=>$id])[0];
                 $commentValidatingAuto = false;
                 if (array_search('commentValidatingAuto', $post->getAllowComment())) {
                     $commentValidatingAuto = true;
                 }


                 $com->setEvent($post)
                     ->setCreatedAt(new \DateTime())
                     ->setApproved($commentValidatingAuto);
                 $this->em->persist($com);
                 $this->em->flush();

                 $this->addFlash('success',"Commentaire ajouté avec succés :)");

                 return $this->redirectToRoute('event.show', array('slug' => $post->getSlug()));

             } else{
               $this->addFlash('error',"Un problème est survenu nous y travaillons ! :)");
            }

            return $this->redirect($req->headers->get('referer'));
        }
    }

    /**
     * @Route("/comment/delete/{comment}", name="delete.comment", methods={"GET"})
     */
    public function delete(Comment $comment, Security $security, Request $req)
    {
     //   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($security->getUser() === $comment->getUser()) {
            $referer = explode('/', $req->headers->get('referer'));
            //dd($jobRepo->find($id));



        }

        return $this->redirect($req->headers->get('referer'));

        //dd($comment);

    }

    /**
     * @Route("/comment/edit/{comment}", name="comment_edit")
     */
    public function edit(Comment $comment, Request $request)
    {

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTextComment($form["textComment"]->getData());
            $this->em->flush();

        }
        return new Response;
    }

}
