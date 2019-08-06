<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\EquipmentRepository")
 */
class Equipment
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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $iconUrl = 'https://www.freeiconspng.com/uploads/error-icon-4.png';

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $sliderImage = 'https://www.freeiconspng.com/uploads/error-icon-4.png';

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $howManyBuyers = 0;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $promotion;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $serverId;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPromotion()
    {
        return $this->promotion;
    }

    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

    public function getServerId()
    {
        return $this->serverId;
    }

    public function setServerId($serverId)
    {
        $this->serverId = $serverId;
    }

    public function getSliderImage()
    {
        return $this->sliderImage;
    }

    public function setSliderImage($sliderImage)
    {
        $this->sliderImage = $sliderImage;
    }

    public function getHowManyBuyers()
    {
        return $this->howManyBuyers;
    }

    public function increaseCounterOfBuying()
    {
        $this->howManyBuyers++;
    }

    public function setHowManyBuyers($howManyBuyers)
    {
        $this->howManyBuyers = $howManyBuyers;
    }
}
