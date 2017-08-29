<?php

namespace AppBundle\Contact;

class Contact {
    private $fullname;
    private $email;
    private $subject;
    private $message;
    private $contactedAt;

    public function setFullname($fullname) {
        $this->fullname = $fullname;

        return $this;
    }
    public function getFullname() {
       return  $this->fullname;
    }

    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }
    public function getEmail() {
        return $this->email;
    }

    public function setSubject($subject) {
        $this->subject = $subject;

        return $this;
    }
    public function getSubject() {
        return $this->subject;
    }

    public function setMessage($message) {
        $this->message = $message;

        return $this;
    }
    public function getMessage() {
        return $this->email;
    }

    public function setContactedAt($contactedAt) {
        $this->contactedAt = $contactedAt;

        return $this;
    }
    public function getContactedAt() {
        return $this->contactedAt;
    }

}