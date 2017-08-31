<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use AppBundle\Form\Type\PostEditType;
use Doctrine\ORM\EntityManagerInterface;

class PostController extends Controller
{
    /**
     * @Route("/post", name="post")
     * @Method({"GET", "POST"})
     */
    public function postAction(Request $request, EntityManagerInterface $em, UserInterface $user = null)
    {
        $post = $user ? Post::createFromUser($user) : Post::create();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setPublishedAt(new \DateTime());
            $user->setIsAdmin(false);

            $em->persist($post);// prepare to insert into the database
            $em->flush();// execute all SQL queries

            $this->addFlash('success', 'Post sent!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('post/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/posts", name="admin_post_list")
     * @Method("GET")
     */
     public function listAction(Request $request)
     {
         $em = $this->getDoctrine()->getEntityManager();
 
         $filter = $request->query->get('filter', 'all');
         $posts = $em->getRepository(Post::class)->findForList($filter);
 
         return $this->render('post/admin/list.html.twig', [
             'posts' => $posts
         ]);
     }

     /**
     * @Route("/admin/posts/{id}", name="admin_post_show")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method("GET")
     */
     public function showAction(Request $request, Post $post)
     {
         return $this->render('post/admin/show.html.twig', [
             'post' => $post,
         ]);
     }

     /**
     * @Route("/admin/posts/{id}/edit", name="admin_post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $form = $this->createForm(PostEditType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Post updated!');

            return $this->redirectToRoute('admin_post_list');
        }

        return $this->render('post/admin/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

      /**
     * Deletes a Post entity.
     *
     * @Route("/admin/{id}/delete", name="admin_post_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post deleted');

        return $this->redirectToRoute('admin_post_list');
    }
}
