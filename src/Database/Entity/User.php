<?php

namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="ModernGame\Database\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email jest zajęty.")
 * @UniqueEntity(fields="username", message="Nick jest zajęty.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=16, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="16")
     */
    public $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    public $password;

    /**
     * @SWG\Property(type="array", @SWG\Items(type="string"))
     * @ORM\Column(type="array")
     */
    public ?array $roles;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\EqualTo(true)
     */
    public $rules;

    /**
     * @SWG\Property(type="string")
     */
    public $reCaptcha;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function hasRules()
    {
        return $this->rules;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    public function getReCaptcha()
    {
        return $this->reCaptcha;
    }

    public function setReCaptcha($reCaptcha)
    {
        $this->reCaptcha = $reCaptcha;
    }

    /**
     * @return string
     */
    public function getSalt() {}

    public function eraseCredentials() {}
}
