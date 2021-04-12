<?php

namespace MNGame\Dto;

class MicroSMSDto
{
    private ?int $userId = null;
    private ?int $serviceId = null;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId)
    {
        $this->userId = $userId;
    }

    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    public function setServiceId(?int $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function toArray(): array {
        return [
            'serviceId' => $this->serviceId,
            'userId' => $this->userId
        ];
    }
}