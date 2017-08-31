<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserRegistrationType;
use AppBundle\Form\Type\UserUpdatePasswordType;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    /**
     * @Route("/update-password", name="update_password")
     * @Method({"GET", "POST"})
     */
    public function updatePasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, UserInterface $user)
    {
        $form = $this->createForm(UserUpdatePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('new_password')->getData();

            $password = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($password);

            $em->flush();// execute all SQL queries

            $this->addFlash('success', 'Password updated!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register", name="register")
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $user = new User((string) Uuid::uuid4());

        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRegisteredAt(new \DateTime());
            $user->setIsAdmin(false);

            $plainPassword = $form->get('password')->getData();

            $password = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($password);

            $em->persist($user);// prepare to insert into the database
            $em->flush();// execute all SQL queries

            $this->addFlash('success', 'Welcome!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/update-profile", name="update_profile")
     * @Method({"GET", "POST"})
     */
     public function updateProfileAction(Request $request, EntityManagerInterface $em, UserInterface $user)
     {
         $form = $this->createForm(UserUpdateProfileType::class);
 
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
 
             $em->flush();// execute all SQL queries
 
             $this->addFlash('success', 'Profile updated!');
 
             return $this->redirectToRoute('homepage');
         }
 
         return $this->render('user/update_profile.html.twig', [
             'form' => $form->createView(),
         ]);
     }

    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     */
    public function loginAction(AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

     /**
     * @Route("/admin/users", name="admin_user_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $filter = $request->query->get('filter', 'all');
        $users = $em->getRepository(User::class)->findForList($filter);

        return $this->render('user/admin/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/{id}", name="admin_user_show")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method("GET")
     */
     public function showAction(Request $request, User $user)
     {
         return $this->render('user/admin/show.html.twig', [
             'user' => $user,
         ]);
     }

    /**
     * @Route("/profile/", name="profile")
     * @Method("GET")
     */
    public function profileAction(Request $request)
    {
        return $this->render('user/profile.html.twig');
    }
}
