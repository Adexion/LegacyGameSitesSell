<?php

namespace ModernGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\ItemListStatisticRepository")
 */
class ItemListStatistic
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
     * @ORM\ManyToOne(targetEntity="ItemList", fetch="EAGER")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    public $itemList;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function getDate()
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getItemList()
    {
        return $this->itemList;
    }

    public function setItemList($itemList)
    {
        $this->itemList = $itemList;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }
}
