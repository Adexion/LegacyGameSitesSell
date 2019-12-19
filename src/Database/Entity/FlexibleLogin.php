<?php
namespace ModernGame\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class FlexibleLogin
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $UserID;

    /**
     * @ORM\Column(type="binary", length=16)
     * @Assert\NotBlank()
     */
    public $UUID;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public $Username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public $Password;

    /**
     * @ORM\Column(type="binary", length=32)
     * @Assert\NotBlank()
     */
    public $IP;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    public $LastLogin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public $Email;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    public bool $LoggedIn = false;

    public function setUserID($UserID)
    {
        $this->UserID = $UserID;
    }

    public function setUUID($UUID)
    {
        $this->UUID = $UUID;
    }

    public function setUsername($Username)
    {
        $this->Username = $Username;
    }

    public function setPassword($Password)
    {
        $this->Password = $Password;
    }

    public function setIP($IP)
    {
        $this->IP = $IP;
    }

    public function setLastLogin($LastLogin)
    {
        $this->LastLogin = $LastLogin;
    }

    public function setEmail($Email)
    {
        $this->Email = $Email;
    }

    public function setLoggedIn($LoggedIn)
    {
        $this->LoggedIn = $LoggedIn;
    }
}
