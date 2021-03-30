<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private ?string $subhead = null;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     * @Assert\Length(min="6")
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(min="6")
     */
    private ?string $text = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min="6", max="256")
     */
    private ?string $shortText = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="author", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ?User $author = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private ?string $serverId = null;

    public function __construct()
    {
        $this->createdAt = (new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getSubhead(): ?string
    {
        return $this->subhead;
    }

    public function setSubhead(?string $subhead)
    {
        $this->subhead = $subhead;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image)
    {
        $this->image = $image;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text)
    {
        $this->text = $text;
    }

    public function getShortText(): ?string
    {
        return $this->shortText;
    }

    public function setShortText(?string $shortText)
    {
        $this->shortText = $shortText;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author)
    {
        $this->author = $author;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt)
    {
        $this->createdAt = $createdAt ?? new DateTime();
    }

    public function getServerId(): ?string
    {
        return $this->serverId;
    }

    public function setServerId(?string $serverId)
    {
        $this->serverId = $serverId;
    }
}
