<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private $subhead;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     * @Assert\Length(min="6")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotBlank()
     * @Assert\Length(min="6", max="256")
     */
    private $shortText;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="16")
     */
    private $author;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getSubhead()
    {
        return $this->subhead;
    }

    public function setSubhead($subhead)
    {
        $this->subhead = $subhead;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getShortText()
    {
        return $this->shortText;
    }

    public function setShortText($shortText)
    {
        $this->shortText = $shortText;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }
}
