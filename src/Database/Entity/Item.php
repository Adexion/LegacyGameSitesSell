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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $iconUrl = 'https://www.freeiconspng.com/uploads/error-icon-4.png';

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $command;

    /**
     * @ORM\ManyToOne(targetEntity="ItemList")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $itemList;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setCommand($command)
    {
        $this->command = $command;
    }

    public function getItemList()
    {
        return $this->itemList;
    }

    public function setItemList($itemList)
    {
        $this->itemList = $itemList;
    }
}
