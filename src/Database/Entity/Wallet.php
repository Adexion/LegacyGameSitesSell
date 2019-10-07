<?php

namespace ModernGame\Database\Entity;

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
    public $id;

    /**
     * @ORM\Column(type="float")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $cash;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    public $user;

    public function __construct()
    {
        $this->cash = 0;
    }

    /**
     * @return float|null
     */
    public function getCash()
    {
        return $this->cash;
    }

    public function setCash($cash)
    {
        $this->cash = $cash;
    }

    public function increaseCash($cash)
    {
        $this->cash = round($cash + $this->cash, 2);
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return integer|null
     */
    public function getId()
    {
        return $this->id;
    }
}
