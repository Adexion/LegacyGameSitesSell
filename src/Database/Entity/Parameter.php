<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\ParameterRepository")
 */
class Parameter
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, name="param_name")
     * @Assert\NotBlank()
     */
    public ?string $name = null;

    /**
     * @ORM\Column(type="text", name="param_value")
     * @Assert\NotBlank()
     */
    private ?string $value = null;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private ?bool $editable = false;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private ?bool $multiple = false;

    /**
     * @ORM\Column(type="integer", name="param_order")
     */
    public ?int $order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value)
    {
        $this->value = $value;
    }

    public function isEditable(): ?bool
    {
        return $this->editable;
    }

    public function setEditable(?bool $editable)
    {
        $this->editable = $editable;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order)
    {
        $this->order = $order;
    }

    public function getMultiple(): ?bool
    {
        return $this->multiple;
    }

    public function setMultiple(?bool $multiple): void
    {
        $this->multiple = $multiple;
    }
}