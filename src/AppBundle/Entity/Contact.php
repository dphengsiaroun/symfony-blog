<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contacted_at", type="datetime")
     */
    private $contactedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="processed_at", type="datetime", nullable=true)
     */
    private $processedAt;

    public static function create()
    {
        return new self();
    }

    public static function createFromUser(User $user)
    {
        $contact = new self();

        $contact->setFullname($user->getFullname());
        $contact->setEmail($user->getEmail());

        return $contact;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Contact
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Contact
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Contact
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set contactedAt
     *
     * @param \DateTime $contactedAt
     *
     * @return Contact
     */
    public function setContactedAt($contactedAt)
    {
        $this->contactedAt = $contactedAt;

        return $this;
    }

    /**
     * Get contactedAt
     *
     * @return \DateTime
     */
    public function getContactedAt()
    {
        return $this->contactedAt;
    }

    /**
     * Set processedAt
     *
     * @param \DateTime $processedAt
     *
     * @return Contact
     */
    public function setProcessedAt(\DateTime $processedAt = null)
    {
        $this->processedAt = $processedAt;

        return $this;
    }

    /**
     * Get processedAt
     *
     * @return \DateTime
     */
    public function getProcessedAt()
    {
        return $this->processedAt;
    }

    public function isProcessed()
    {
        return null !== $this->processedAt;
    }
}
