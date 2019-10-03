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
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ItemList")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $itemList;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $boughtAt;

    public function __construct()
    {
        $this->boughtAt = new DateTime();
    }

    public function getBoughtAt()
    {
        return $this->boughtAt->format('Y-m-d H:i:s');
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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
