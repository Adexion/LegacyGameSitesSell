<?php

namespace ModernGame\Database\Entity;

use App\Zeus\Service\ContactInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $contactId;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $email;


    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank()
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $status;

    private $reCaptcha;

    public function getContactId()
    {
        return $this->contactId;
    }

    public function setContactId($contactId)
    {
        $this->contactId = $contactId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setContactMessage(Request $request)
    {

    }

    public function getReCaptcha()
    {
        return $this->reCaptcha;
    }

    public function setReCaptcha($reCaptcha)
    {
        $this->reCaptcha = $reCaptcha;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
}
