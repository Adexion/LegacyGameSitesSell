<?php

namespace MNGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use MNGame\Enum\PaySafeCardStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class PaySafeCard
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private ?float $money;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private ?string $code;

    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private ?User $user;

    /**
     * @ORM\Column(type="integer", options={"default" : 0}, nullable=false)
     * @Assert\NotBlank()
     */
    private ?int $used = PaySafeCardStatusEnum::NOT_USED;

    public function getMoney(): ?float
    {
        return $this->money;
    }

    public function setMoney(?float $money)
    {
        $this->money = $money;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code)
    {
        $this->code = $code;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
    }

    public function isUsed(): ?int
    {
        return $this->used;
    }

    public function setUsed(?int $used)
    {
        $this->used = $used;
    }
}
