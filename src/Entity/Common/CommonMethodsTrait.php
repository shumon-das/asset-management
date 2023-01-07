<?php

namespace App\Entity\Common;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\Location;
use App\Entity\Products;
use App\Entity\Vendors;
use DateTimeImmutable;
use Exception;

trait CommonMethodsTrait
{
    /**
     * @throws Exception
     */
    private function commonMethods(Location|Department|Vendors|Products|Employee $entity, bool $update): Location|Department|Vendors|Products|Employee
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $createdAt = $update
                         ? new DateTimeImmutable($entity->getCreatedAt()->format('Y-m-d h:i:s'))
                         : new DateTimeImmutable();
        $entity
            ->setUpdatedAt($update ? new DateTimeImmutable() : null)
            ->setUpdatedBy($update ? $user->getId() : null)
            ->setCreatedAt($createdAt)
            ->setCreatedBy($entity->getCreatedBy() ?? $user->getId())
            ->setDeletedAt(null)
            ->setDeletedBy(null)
            ->setIsDeleted(0)
        ;
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }
}