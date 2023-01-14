<?php

namespace App\Entity\Methods;

use App\Entity\Location;
use Exception;

trait LocationMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function locationMethods(Location $location, $request, bool $update = false): Location
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