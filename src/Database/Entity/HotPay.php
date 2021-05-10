<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class HotPay
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $secret = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private ?string $email = null;

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(?string $secret)
    {
        $this->secret = $secret;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}