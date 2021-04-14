<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email jest zajęty.")
 * @UniqueEntity(fields="username", message="Nick jest zajęty.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=16, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="16")
     */
    private ?string  $username = null;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="array")
     */
    private ?array $roles;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\EqualTo(true)
     */
    private ?bool $rules = false;

    /**
     * @ORM\Column(type="boolean",  options={"default" : true})
     */
    private ?bool $commercial = false;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * @Assert\Length(min="3", max="16")
     */
    private ?string $referral = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $assignedServerId;

    private ?string $reCaptcha = null;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username)
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles)
    {
        $this->roles = $roles;
    }

    public function hasRules(): ?bool
    {
        return $this->rules;
    }

    public function setRules(?bool $rules)
    {
        $this->rules = $rules;
    }

    public function getReCaptcha(): ?string
    {
        return $this->reCaptcha;
    }

    public function setReCaptcha(?string $reCaptcha)
    {
        $this->reCaptcha = $reCaptcha;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email
        ];
    }

    public function getAssignedServerId(): ?int
    {
        return $this->assignedServerId;
    }

    public function setAssignedServerId(?int $assignedServerId)
    {
        $this->assignedServerId = $assignedServerId;
    }

    public function getCommercial(): ?bool
    {
        return $this->commercial;
    }

    public function setCommercial(?bool $commercial)
    {
        $this->commercial = $commercial;
    }

    public function getReferral(): ?string
    {
        return $this->referral;
    }

    public function setReferral(?string $referral): void
    {
        $this->referral = $referral;
    }
}
