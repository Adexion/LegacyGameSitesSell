<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\ItemRepository")
 */
class Item
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
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $icon = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $command = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $serverId = null;

    /**
     * @ORM\ManyToOne(targetEntity="ItemList", fetch="EAGER")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?ItemList $itemList = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon)
    {
        $this->icon = $icon;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function setCommand(?string $command)
    {
        $this->command = $command;
    }

    public function getServerId(): ?string
    {
        return $this->serverId;
    }

    public function setServerId(?string $serverId)
    {
        $this->serverId = $serverId;
    }

    public function getItemList(): ?ItemList
    {
        return $this->itemList;
    }

    public function setItemList(?ItemList $itemList)
    {
        $this->itemList = $itemList;
    }
}
