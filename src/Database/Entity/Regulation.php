<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\RegulationRepository")
 */
class Regulation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="RegulationCategory", fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?RegulationCategory $category = null;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?RegulationCategory
    {
        return $this->category;
    }

    public function setCategory(?RegulationCategory $category)
    {
        $this->category = $category;
    }
}
