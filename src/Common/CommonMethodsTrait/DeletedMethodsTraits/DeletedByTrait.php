<?php

namespace App\Common\CommonMethodsTrait\DeletedMethodsTraits;

trait DeletedByTrait
{
    public function getDeletedBy(): ?int
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?int $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }
}