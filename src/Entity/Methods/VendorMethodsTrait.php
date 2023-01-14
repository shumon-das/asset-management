<?php

namespace App\Entity\Methods;

use App\Entity\Vendors;
use Exception;

trait VendorMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function vendorMethods(Vendors $vendor, $request, bool $update = false): Vendors
    {
        $vendor
            ->setVendorName($request->get('vendor-name'))
            ->setEmail($request->get('vendor-email'))
            ->setPhone($request->get('phone'))
            ->setContactPerson($request->get('contact-person'))
            ->setDesignation($request->get('designation'))
            ->setCountry($request->get('country'))
            ->setState($request->get('state'))
            ->setCity($request->get('city'))
            ->setZipCode($request->get('zip-code'))
            ->setGstinNo($request->get('gstin-no'))
            ->setAddress($request->get('address'))
            ->setDescription($request->get('description'))
            ->setStatus(false);

        return $this->commonMethods($vendor, $update);
    }
}