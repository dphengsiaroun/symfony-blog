<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Contact;
use AppBundle\Form\Type\ContactType;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     * @Method({"GET", "POST"})
     */
    public function contactAction(Request $request, \Swift_Mailer $mailer, UserInterface $user = null)
    {
        $contact = $user ? Contact::createFromUser($user) : Contact::create();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setContactedAt(new \DateTime());

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($contact);// prepare to insert into the database
            $em->flush();// execute all SQL queries

            $message = new \Swift_Message();
            $message->setTo('contact@example.org');
            $message->setFrom([$contact->getEmail() => $contact->getFullname()]);
            $message->setSubject($contact->getSubject());
            $message->setBody($contact->getMessage());

            $mailer->send($message);

            $this->addFlash('success', 'Thank you for you message!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contacts", name="contact_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $filter = $request->query->get('filter', 'all');

        $contacts = $em->getRepository(Contact::class)->findForList($filter);

        return $this->render('contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/contacts/{id}", name="contact_show")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function showAction(Request $request, Contact $contact)
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/contacts/{id}/process", name="contact_process")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function processAction(Request $request, Contact $contact, EntityManagerInterface $em)
    {
        $response = $this->redirectToRoute('contact_show', [
            'id' => $contact->getId()
        ]);

        $token = $request->query->get('token');
        if (!$this->isCsrfTokenValid('contact_process.' . $contact->getId(), $token)) {
            $this->addFlash('error', 'CSRF token invalid.');

            return $response;
        }

        if ($contact->isProcessed()) {
            $this->addFlash('warning', 'Contact already processed.');

            return $response;
        }

        $contact->setProcessedAt(new \DateTime());

        $em->flush();

        $this->addFlash('success', 'Contact sucessfully processed.');

        return $response;
    }
}
