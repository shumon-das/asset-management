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
        $parent = $request->get('parent')
            ? $this->updateParentChildes($request->get('parent'))
            : null;

        $categories
            ->setName($request->get('category-name'))
            ->setLogo($request->get('logo'))
            ->setBadge($request->get('badge') ?? null)
            ->setParent($parent);
        return $this->commonMethods($categories, $update);
    }

    private function updateParentChildes(int $parent): int
    {
        $parentCategory = $this->categoriesRepository->find($parent);
        $lastId = $this->categoriesRepository->findOneBy([], ['id' => 'DESC'])->getId();
        $childes = $parentCategory->getChildes();
        $childes[] = ++$lastId;
        $parentCategory->setChildes($childes);
        $this->entityManager->persist($parentCategory);
        $this->entityManager->flush();
        return $parent;
    }
}