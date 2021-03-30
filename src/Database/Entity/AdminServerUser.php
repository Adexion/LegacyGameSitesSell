<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\AdminServerUserRepository")
 */
class AdminServerUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(min="6")
     */
    private ?string $skinUrl = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private ?string $serverId = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
    }

    public function getSkinUrl(): ?string
    {
        return $this->skinUrl;
    }

    public function setSkinUrl(?string $skinUrl)
    {
        $this->skinUrl = $skinUrl;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getServerId(): ?string
    {
        return $this->serverId;
    }

    public function setServerId(?string $serverId)
    {
        $this->serverId = $serverId;
    }
}