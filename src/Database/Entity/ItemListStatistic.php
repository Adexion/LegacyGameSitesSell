<?php

namespace MNGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\ItemListStatisticRepository")
 */
class ItemListStatistic
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private ?DateTime $date = null;

    /**
     * @ORM\ManyToOne(targetEntity="ItemList", fetch="EAGER")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?ItemList $itemList = null;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function getDate(): ?string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemList(): ?ItemList
    {
        return $this->itemList;
    }

    public function setItemList(?ItemList $itemList)
    {
        $this->itemList = $itemList;
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
