<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\PriceRepository")
 */
class Price
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="integer", unique=true, length=5)
     */
    private ?string $phoneNumber = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $amount = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?int $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount)
    {
        $this->amount = $amount;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price)
    {
        $this->price = $price;
    }
}
