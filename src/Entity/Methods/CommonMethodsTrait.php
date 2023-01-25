<?php

namespace App\Entity\Methods;

use App\Entity\Assets;
use App\Entity\AssigningAssets;
use App\Entity\Categories;
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
    private function commonMethods(
        Location|Department|Vendors|Products|Employee|Assets|AssigningAssets|Categories $entity, bool $update
    ): array
    {
        try {
            /** @var Employee $user */
            $user = $this->security->getUser();
            $createdAt = $update
                             ? new DateTimeImmutable($entity->getCreatedAt()->format('Y-m-d h:i:s'))
                             : new DateTimeImmutable();
            $createdBy = $entity->getCreatedBy() ?? $user->getId();
            $updateAt = $update ? new DateTimeImmutable() : null;
            $updatedBy = $update ? $user->getId() : null;
            $entity
                ->setUpdatedAt($updateAt)
                ->setUpdatedBy($updatedBy)
                ->setCreatedAt($createdAt)
                ->setCreatedBy($createdBy)
                ->setDeletedAt(null)
                ->setDeletedBy(null)
                ->setIsDeleted(0)
            ;
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Exception $exception) {
            return [ 'error' => $exception->getMessage()];
        }
        return $update
            ? ['success' => 'data updated successfully']
            : ['success' => 'data saved successfully'] ;
    }


    private function getVendor(Vendors $vendor, array $ids): array
    {
        return [
            'id' => $vendor->getId(),
            'vendorName' => $vendor->getVendorName(),
            'contactPerson' => $vendor->getContactPerson(),
            'email' => $vendor->getEmail(),
            'gstinNo' => $vendor->getGstinNo(),
            'phone' => $vendor->getPhone(),
            'status' => null,
            'use' => in_array($vendor->getId(), $ids),
        ];
    }

    public function getIdsAsArray(array $findAll, string $entity): array
    {
        $data = [];
        foreach ($findAll as $item) {
            $data[] += $item->{$entity}();
        }
        return $data;
    }
}