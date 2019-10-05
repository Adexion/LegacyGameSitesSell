<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\ItemListRepository")
 */
class ItemList
{
    /**
     * @Groups({"statistic", "history"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"statistic", "history"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Groups({"statistic", "history"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @Groups({"statistic", "history"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $iconUrl = 'https://www.freeiconspng.com/uploads/error-icon-4.png';

    /**
     * @Groups({"statistic", "history"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $sliderImage = 'https://www.freeiconspng.com/uploads/error-icon-4.png';

    /**
     * @Groups({"statistic", "history"})
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $howManyBuyers = 0;

    /**
     * @Groups({"statistic", "history"})
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @Groups({"statistic", "history"})
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $promotion;

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
