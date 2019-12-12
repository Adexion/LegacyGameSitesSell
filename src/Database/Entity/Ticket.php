<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use ModernGame\Database\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $contactId;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public $type;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public $subject;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public $message;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank()
     */
    public $token;

    /**
     * @ORM\Column(type="string", length=512)
     */
    public $status;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    public $user;

    public $reCaptcha;

    public function getContactId()
    {
        return $this->contactId;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function clearUser()
    {
        $this->user = null;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
