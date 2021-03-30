<?php

namespace MNGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use MNGame\Enum\TicketStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\TicketRepository")
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
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private ?string $type = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private ?string $subject = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private ?string $message = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $datetime = null;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank()
     */
    private ?string $token = null;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private ?int $status = TicketStatusEnum::NOT_READ;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private ?User $user = null;

    private ?string $reCaptcha = null;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status)
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

    public function getDatetime(): ?DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(?DateTime $datetime = null): void
    {
        $this->datetime = $datetime ?? new DateTime();
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
