<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\ModuleEnabledRepository")
 */
class ModuleEnabled
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
    private ?string $route = null;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private ?string $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route)
    {
        $this->route = $route;
    }

    public function isActive(): ?string
    {
        return $this->active;
    }

    public function setActive(?string $active)
    {
        $this->active = $active;
    }
}