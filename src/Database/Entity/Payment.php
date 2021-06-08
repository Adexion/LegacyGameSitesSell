<?php

namespace MNGame\Database\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use MNGame\Enum\PaymentTypeEnum;
use ReflectionException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToMany(targetEntity="Server", mappedBy="id")
     * @Assert\NotBlank
     */
    private ?Collection $servers = null;

    /**
     * @ORM\ManyToMany(targetEntity="Configuration", inversedBy="id")
     * @ORM\JoinTable(name="payment_configuration")
     */
    private ?Collection $configurations = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private ?int $type = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private ?string $name = null;

    /**
     * @throws ReflectionException
     */
    public function getType(): ?PaymentTypeEnum
    {
        return new PaymentTypeEnum($this->type);
    }

    public function setType(?PaymentTypeEnum $type)
    {
        $this->type = $type->getValue();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getServers(): ?Collection
    {
        return $this->servers;
    }

    public function setServers(?Collection $servers)
    {
        $this->servers = $servers;
    }

    public function getConfigurations(): ?Collection
    {
        return $this->configurations;
    }

    public function setConfigurations(?Collection $configurations)
    {
        $this->configurations = $configurations;
    }
}