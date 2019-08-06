<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\CustomerRepository")
 */
class Customer
{
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $customerId;

    /**
     * @ORM\Column(type="integer")
     * @OneToOne(targetEntity="User")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="string", unique=true, length=25)
     * @Assert\NotBlank()
     */
    private $invitationCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getInvitationCode(): string
    {
        return $this->invitationCode;
    }

    public function setInvitationCode(string $invitationCode)
    {
        $this->invitationCode = $invitationCode;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}
