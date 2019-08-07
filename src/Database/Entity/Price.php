<?php

namespace ModernGame\Database\Entity;

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
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true, length=5)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $amount;

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

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }
}
