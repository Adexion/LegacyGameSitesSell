<?php

namespace MNGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\PaymentHistoryRepository")
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
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
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

    /**
     * @ORM\Column(type="string")
     */
    private ?string $paymentId = null;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('HOT_PAY_SMS', 'DIRECT_BILL', 'HOT_PAY', 'PAY_SAFE_CARD', 'VOUCHER', 'MICRO_SMS')")
     */
    private ?string $paymentType = null;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('SUCCESS', 'PENDING', 'FAILURE')")
     */
    private ?string $paymentStatus = null;

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

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function setPaymentId(?string $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(?string $paymentType)
    {
        $this->paymentType = $paymentType;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(?string $paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
    }
}
