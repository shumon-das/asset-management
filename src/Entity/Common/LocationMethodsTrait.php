<?php

namespace App\Entity\Common;

use App\Entity\Location;

trait LocationMethodsTrait
{
    use CommonMethodsTrait;
    public function locationMethods(Location $location, $request, bool $update): Location
    {
        $location
            ->setOfficName($request->get('office-name'))
            ->setCountry($request->get('country'))
            ->setState($request->get('state'))
            ->setCity($request->get('city'))
            ->setZipCode($request->get('zip-code'))
            ->setContactPersonName($request->get('contact-person-name'))
            ->setAddress1($request->get('address1'))
            ->setAddress2($request->get('address2'));

        return $this->commonMethods($location, $update);
    }
}