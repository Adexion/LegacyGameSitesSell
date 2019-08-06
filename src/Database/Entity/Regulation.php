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
     * @ORM\Column(type="string", length=512)
     * @Assert\NotBlank()
     */
    private $categoryId;

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

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getRegulationId()
    {
        return $this->regulationId;
    }

    public function setRegulationId($regulationId)
    {
        $this->regulationId = $regulationId;
    }

}
