<?php

namespace ModernGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\PaymentHistoryRepository")
 */
class PaymentHistory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private ?DateTime $date = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $amount = null;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount)
    {
        $this->amount = $amount;
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
