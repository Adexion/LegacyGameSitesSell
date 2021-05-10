<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\ItemListRepository")
 */
class ItemList
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
     */
    public ?string $name = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $icon = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $sliderImage = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private ?int $howManyBuyers = 0;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private ?float $price = 0;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private ?float $promotion = 0;

    /**
     * @ORM\OneToOne(targetEntity="SMSPrice", fetch="EAGER")
     * @ORM\JoinColumn(name="sms_price_id", referencedColumnName="id")
     */
    private ?SMSPrice $smsPrice = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private ?string $serverId = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon)
    {
        $this->icon = $icon;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price)
    {
        $this->price = $price;
    }

    public function getPromotion(): ?float
    {
        return $this->promotion;
    }

    public function setPromotion(?float $promotion)
    {
        $this->promotion = $promotion;
    }

    public function getSliderImage(): ?string
    {
        return $this->sliderImage;
    }

    public function setSliderImage(?string $sliderImage)
    {
        $this->sliderImage = $sliderImage;
    }

    public function getHowManyBuyers(): ?int
    {
        return $this->howManyBuyers;
    }

    public function increaseCounterOfBuying()
    {
        $this->howManyBuyers++;
    }

    public function setHowManyBuyers(?int $howManyBuyers)
    {
        $this->howManyBuyers = $howManyBuyers;
    }

    public function getSmsPrice(): ?SMSPrice
    {
        return $this->smsPrice;
    }

    public function setSmsPrice(?SMSPrice $smsPrice)
    {
        $this->smsPrice = $smsPrice;
    }

    public function getAfterPromotionPrice(): ?float
    {
        return round($this->getPrice() - ($this->getPrice() * $this->getPromotion()), 2);
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
