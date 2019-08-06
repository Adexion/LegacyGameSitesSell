<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

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
    private $id;

    /**
     * @ORM\Column(type="float")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cash;

    /**
     * @ORM\Column(type="integer")
     * @OneToOne(targetEntity="User")
     */
    private $userId;

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
     * @return integer|null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return integer|null
     */
    public function getId()
    {
        return $this->id;
    }
}
