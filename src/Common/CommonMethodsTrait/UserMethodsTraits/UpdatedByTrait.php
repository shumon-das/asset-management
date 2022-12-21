<?php

namespace App\Common\CommonMethodsTrait\UserMethodsTraits;

trait UpdatedByTrait
{
    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?int $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}