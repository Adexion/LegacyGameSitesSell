<?php

namespace ModernGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\PaymentHistoryRepository")
 */
class PaymentHistory
{
    /**
     * @Groups({"history"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"history"})
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @Groups({"history"})
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

    /**
     * @Groups({"history"})
     * @ORM\Column(type="float")
     */
    private $amount;

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
        return $this->date;
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
