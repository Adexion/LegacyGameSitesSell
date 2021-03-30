<?php

namespace MNGame\Database\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\UserRepository")
 */
class Token
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $token = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $date = null;

    public function __construct()
    {
        $this->date = new DateTime('+24 hours');
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token)
    {
        $this->token = $token;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
    }

    public function getDate(): ?string
    {
        return $this->date->format('Y-m-d H:i:s');
    }
}
