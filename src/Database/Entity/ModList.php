<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\ModListRepository")
 */
class ModList
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $modId;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $link;

    public function getModId()
    {
        return $this->modId;
    }

    public function setModId($modId)
    {
        $this->modId = $modId;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }
}
