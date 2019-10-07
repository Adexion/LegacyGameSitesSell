<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\PriceRepository")
 */
class Price
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="integer", unique=true, length=5)
     */
    public $phoneNumber;

    /**
     * @ORM\Column(type="float")
     */
    public $amount;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhoneNumber(): int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }
}
