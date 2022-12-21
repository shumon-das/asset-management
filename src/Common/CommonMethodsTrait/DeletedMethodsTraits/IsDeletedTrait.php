<?php

namespace App\Common\CommonMethodsTrait\DeletedMethodsTraits;

trait IsDeletedTrait
{
    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}