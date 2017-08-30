<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
     public function listAction(Request $request)
     {
         $em = $this->getDoctrine()->getEntityManager();
 
         $filter = $request->query->get('filter', 'all');
         $posts = $em->getRepository(Post::class)->findForList($filter);
 
         return $this->render('default/index.html.twig', [
             'posts' => $posts
         ]);
     }

     /**
     * @Route("/posts/{id}", name="post_show")
     * @Method("GET")
     */
     public function showAction(Request $request, Post $post)
     {
         return $this->render('default/show.html.twig', [
             'post' => $post
         ]);
     }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        return $this->render('default/about.html.twig');
    }
}
