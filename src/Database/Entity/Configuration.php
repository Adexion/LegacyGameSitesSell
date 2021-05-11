<?php

namespace MNGame\Database\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Configuration
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?int $value = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?int $name = null;

    /**
     * @ORM\ManyToMany(targetEntity="Payment", mappedBy="id")
     */
    private ?Collection $payment = null;

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value)
    {
        $this->value = $value;
    }

    public function getName(): ?int
    {
        return $this->name;
    }

    public function setName(?int $name)
    {
        $this->name = $name;
    }

    public function getPayment(): ?Collection
    {
        return $this->payment;
    }

    public function setPayment(?Collection $payment)
    {
        $this->payment = $payment;
    }
}