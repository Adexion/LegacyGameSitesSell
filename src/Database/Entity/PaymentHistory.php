<?php

namespace ModernGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

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
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $userId;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $date;

    /**
     * @ORM\Column(type="float")
     */
    public $amount;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}
