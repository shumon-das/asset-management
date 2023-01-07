<?php

namespace App\Entity\Common;

use App\Entity\Assets;
use App\Entity\AssigningAssets;
use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\Location;
use App\Entity\Products;
use App\Entity\Vendors;
use DateTimeImmutable;
use Exception;
use Symfony\Component\HttpFoundation\Request;

trait CommonMethodsTrait
{
    /**
     * @throws Exception
     */
    private function commonMethods(
        Location|Department|Vendors|Products|Employee|Assets|AssigningAssets $entity, Request $request, mixed $repository
    ): Location|Department|Vendors|Products|Employee|Assets|AssigningAssets
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $id = $request->request->get('id');
        if($id) {
            $dbData =$repository->find($id);
            $entity
                ->setUpdatedAt(new DateTimeImmutable())
                ->setUpdatedBy($user->getId())
                ->setCreatedBy($dbData->getCreatedBy())
                ->setCreatedAt(new DateTimeImmutable($dbData->getCreatedAt()->format('Y-m-d h:i:s')));
        } else {
            $entity
                ->setCreatedAt(new DateTimeImmutable())
                ->setCreatedBy($user->getId());
        }

        return $entity
                 ->setIsDeleted(0)
                 ->setDeletedAt(null)
                 ->setDeletedBy(null);
    }
}