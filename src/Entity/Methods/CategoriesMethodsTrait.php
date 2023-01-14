<?php

namespace App\Entity\Methods;

use App\Entity\Categories;
use Exception;

trait CategoriesMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function categoriesMethods(Categories $categories, $request, bool $update = false): Categories
    {
//        dd($request->get('category-name'));
        $categories
            ->setName($request->get('category-name'))
            ->setLogo($request->get('logo'))
            ->setBadge($request->get('badge') ?? null)
            ->setParent($request->get('parent') ?? null);
        return $this->commonMethods($categories, $update);
    }
}