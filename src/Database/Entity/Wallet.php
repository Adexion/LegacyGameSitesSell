<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="float")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?float $cash = null;

    /**
     * @ORM\OneToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?User $user = null;

    public function __construct()
    {
        $this->cash = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCash(): ?float
    {
        return $this->cash;
    }

    public function setCash(?float $cash)
    {
        $this->cash = round($cash, 2);
    }

    public function increaseCash(?float $cash)
    {
        $this->cash = round($cash + $this->cash, 2);
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
