<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\RegulationRepository")
 */
class Regulation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $regulationId;

    /**
     * @ORM\ManyToOne(targetEntity="RegulationCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getRegulationId()
    {
        return $this->regulationId;
    }

    public function setRegulationId($regulationId)
    {
        $this->regulationId = $regulationId;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }
}
