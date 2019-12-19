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
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $image = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $name = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    public ?string $link = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image)
    {
        $this->image = $image;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
    }
}
