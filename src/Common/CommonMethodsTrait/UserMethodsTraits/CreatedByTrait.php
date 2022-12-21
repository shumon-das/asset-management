<?php

namespace App\Common\CommonMethodsTrait\UserMethodsTraits;

trait CreatedByTrait
{
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}