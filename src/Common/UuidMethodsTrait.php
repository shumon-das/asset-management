<?php

namespace App\Common;

use Symfony\Component\Uid\Uuid;

trait UuidMethodsTrait
{
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}