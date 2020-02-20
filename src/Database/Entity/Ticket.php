<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
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
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $name = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $email = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $type = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $subject = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $message = null;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank()
     */
    public ?string $token = null;

    /**
     * @ORM\Column(type="string", length=512)
     */
    public ?string $status = null;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    public ?User $user = null;

    public ?string $reCaptcha = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type)
    {
        $this->type = $type;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject)
    {
        $this->subject = $subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message)
    {
        $this->message = $message;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status)
    {
        $this->status = $status;
    }

    public function getReCaptcha(): ?string
    {
        return $this->reCaptcha;
    }

    public function setReCaptcha(?string $reCaptcha)
    {
        $this->reCaptcha = $reCaptcha;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token)
    {
        $this->token = $token;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
    }
}
