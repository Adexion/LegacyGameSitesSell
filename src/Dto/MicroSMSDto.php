<?php

namespace MNGame\Dto;

class MicroSMSDto
{
    private ?int $userID = null;
    private ?int $ServiceID = null;

    public function getUserID(): ?int
    {
        return $this->userID;
    }

    public function setUserID(?int $userID)
    {
        $this->userID = $userID;
    }

    public function getServiceID(): ?int
    {
        return $this->ServiceID;
    }

    public function setServiceID(?int $ServiceID)
    {
        $this->ServiceID = $ServiceID;
    }
}