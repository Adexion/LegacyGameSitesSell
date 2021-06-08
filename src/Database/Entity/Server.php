<?php

namespace MNGame\Database\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use MNGame\Enum\ExecutionTypeEnum;
use MNGame\Enum\PaymentTypeEnum;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="MNGame\Database\Repository\ServerRepository")
 */
class Server
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public ?string $host = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private ?int $port = null;

    /**
     * Caution!! must be plain text
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $password = null;

    /**
     * @ORM\ManyToMany(targetEntity="Payment", inversedBy="id")
     * @ORM\JoinTable(name="server_payment")
     * @Assert\NotBlank
     */
    private ?Collection $payments = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $userOnlineCommand = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private ?string $executionType = null;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private ?string $playerNotFoundCommunicate = null;

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

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(?int $port)
    {
        $this->port = $port;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    public function getUserOnlineCommand(): ?string
    {
        return $this->userOnlineCommand;
    }

    public function setUserOnlineCommand(?string $userOnlineCommand)
    {
        $this->userOnlineCommand = $userOnlineCommand;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image)
    {
        $this->image = $image;
    }

    /**
     * @throws ReflectionException
     */
    public function getExecutionType(): ?ExecutionTypeEnum
    {
        return new ExecutionTypeEnum($this->executionType);
    }

    public function setExecutionType(?ExecutionTypeEnum $executionType)
    {
        $this->executionType = $executionType->getValue();
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host)
    {
        $this->host = $host;
    }

    public function getPlayerNotFoundCommunicate(): ?string
    {
        return $this->playerNotFoundCommunicate;
    }

    public function setPlayerNotFoundCommunicate(?string $playerNotFoundCommunicate)
    {
        $this->playerNotFoundCommunicate = $playerNotFoundCommunicate;
    }

    public function getPayments(): ?Collection
    {
        return $this->payments;
    }

    public function setPayments(?Collection $payments)
    {
        $this->payments = $payments;
    }

    /**
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function getPaymentByType(PaymentTypeEnum $paymentTypeEnum): Payment {
        /** @var Payment $payment */
        foreach ($this->payments->getValues() as $payment) {
            if ($payment->getType() === $paymentTypeEnum) {
                return $payment;
            }
        }

        throw new RuntimeException('Payment '.$paymentTypeEnum->getKey().' was not found');
    }
}