<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\CommentType;
use Doctrine\ORM\EntityManagerInterface;

class CommentController extends Controller
{
    /**
     * @Route("/comment/{post_id}", name="comment")
     * @Method({"GET", "POST"})
     */
    public function commentAction(Request $request, EntityManagerInterface $em, UserInterface $user = null, Post $post)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPublishedAt(new \DateTime());
            $comment->setUserId($user->getId());
            // $comment->setPostId($post->getPostId());


            $em->persist($comment);// prepare to insert into the database
            $em->flush();// execute all SQL queries

            $this->addFlash('success', 'Comment sent!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('comment/comment.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
