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
    public $LoggedIn = 0;

    /**
     * @param mixed $UserID
     */
    public function setUserID($UserID)
    {
        $this->UserID = $UserID;
    }

    /**
     * @param mixed $UUID
     */
    public function setUUID($UUID)
    {
        $this->UUID = $UUID;
    }

    /**
     * @param mixed $Username
     */
    public function setUsername($Username)
    {
        $this->Username = $Username;
    }

    /**
     * @param mixed $Password
     */
    public function setPassword($Password)
    {
        $this->Password = $Password;
    }

    /**
     * @param mixed $IP
     */
    public function setIP($IP)
    {
        $this->IP = $IP;
    }

    /**
     * @param mixed $LastLogin
     */
    public function setLastLogin($LastLogin)
    {
        $this->LastLogin = $LastLogin;
    }

    /**
     * @param mixed $Email
     */
    public function setEmail($Email)
    {
        $this->Email = $Email;
    }

    /**
     * @param mixed $LoggedIn
     */
    public function setLoggedIn($LoggedIn)
    {
        $this->LoggedIn = $LoggedIn;
    }
}
