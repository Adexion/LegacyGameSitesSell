<?php

namespace ModernGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\ItemListStatisticRepository")
 */
class ItemListStatistic
{
    /**
     * @Groups({"statistic"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"statistic"})
     * @ORM\ManyToOne(targetEntity="ItemList")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $itemList;

    /**
     * @Groups({"statistic"})
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @Groups({"statistic"})
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

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }
}
