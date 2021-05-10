<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\SMSPriceRepository")
 */
class SMSPrice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private ?string $id = null;

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
