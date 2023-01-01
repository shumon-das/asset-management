<?php

namespace App\Entity\Common;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\Location;
use DateTimeImmutable;

trait CommonMethodsTrait
{
    private function commonMethods(Location|Department $entity, bool $update): Location|Department
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $updatedAt = true === $update ? new DateTimeImmutable() : null;
        $updatedBy = true === $update ? $user->getId() : null;
        return $entity
            ->setIsDeleted(0)
            ->setUpdatedAt($updatedAt)
            ->setDeletedAt(null)
            ->setCreatedBy($user->getId())
            ->setUpdatedBy($updatedBy)
            ->setDeletedBy(null)
            ->setCreatedAt(new DateTimeImmutable());
    }
}