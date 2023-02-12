<?php

namespace App\Common\Uploads;

use App\Entity\Assets;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;

trait UploadAssetsTrait
{
    public function validateAssetsData(array $data): array
    {
        $names = $this->allEntityIdsAndNames();
        $missingData = [];
        foreach ($data as $key => $row) {
            if (0 !== $key
                && false === empty($row[0])
                && false === empty($row[1])
                && false === empty($row[2])
                && false === empty($row[5])
            ) {
                $missingData[] = [
                    'row' => ++$key,
                    'productCondition' => in_array($row[0], $names['productsIds']),
                    'product' => array_flip($names['productsIds'])[$row[0]] ?? 0,
                    'showProduct' => $row[0],
                    'vendorCondition' => in_array(ucfirst($row[1]), $names['vendorsIds']),
                    'vendor' => array_flip($names['vendorsIds'])[$row[1]] ?? 0,
                    'showVendor' => $row[1],
                    'assetName' => $row[2],
                    'serialNumber' => $row[3],
                    'price' => $row[4],
                    'locationCondition' => in_array(ucfirst($row[5]), $names['locationsIds']),
                    'location' => array_flip($names['locationsIds'])[$row[5]] ?? 0,
                    'showLocation' => $row[5],
                    'purchaseDate' => $row[6],
                    'warrantyExpireDate' => $row[7],
                    'purchaseType' => $row[8],
                    'usefulLife' => $row[9],
                    'residualValue' => $row[10],
                    'rate' => $row[11],
                    'description' => $row[12],
                    'itemError' => $this->getAssetItemError($row, $names),
                ];
            }
        }

        $uploadAssetError = [];
        foreach ($missingData as $row) {
            if (false === $row['productCondition']) {
                $uploadAssetError[] = [
                    'product' => $row['product']
                ];
            }
            if (false === $row['vendorCondition']) {
                $uploadAssetError[] = [
                    'vendor' => $row['vendor']
                ];
            }
            if (false === $row['locationCondition']) {
                $uploadAssetError[] = [
                    'location' => $row['location']
                ];
            }
        }

        return [
            'data' => $missingData,
            'uploadError' => $uploadAssetError,
        ];
    }

    private function getAssetItemError(array $row, array $names): bool
    {
        if (false === in_array($row[0], $names['productsIds'])) {
            return true;
        }
        if (false === in_array(ucfirst($row[1]), $names['vendorsIds'])) {
            return true;
        }
        if (false === in_array(ucfirst($row[5]), $names['locationsIds'])) {
            return true;
        }

        return false;
    }
}