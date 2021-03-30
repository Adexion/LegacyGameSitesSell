<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\TutorialRepository")
 */
class Tutorial
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private ?string $question = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private ?string $text = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min="6")
     */
    private ?string $embed = null;

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question)
    {
        $this->question = $question;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text)
    {
        $this->text = $text;
    }

    public function getEmbed(): ?string
    {
        return $this->embed;
    }

    public function setEmbed(?string $embed): void
    {
        $this->embed = $embed;
    }

}