<?php

namespace App\Controller;

use App\Common\NamesTrait;
use App\Entity\Categories;
use App\Entity\Methods\CategoriesMethodsTrait;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractApiController
{
    use NamesTrait;
    use CategoriesMethodsTrait;
    #[Route('/ams/dashboard', name: 'app_home')]
    public function index(): Response
    {
        $asideData = [
            0 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'dashboard',
                'badge' => '',
                'option' => false
            ],
            1 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'vendors',
                'badge' => '',
                'option' => true,
                'sub' => ''
            ],
            2 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'products',
                'badge' => '',
                'option' => false
            ],
            3 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'assets',
                'badge' => '',
                'childes' => [],
                'parent' => null
            ],
            4 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'exports',
                'badge' => '',
                'option' => false
            ],
            5 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'admin',
                'badge' => '',
                'option' => true
            ],
            6 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'upload',
                'badge' => '',
                'option' => false
            ],
            7 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'recycle bin',
                'badge' => '',
                'option' => false
            ],
            8 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'supports',
                'badge' => '',
                'option' => false
            ],
            9 => [
                'logo' => 'nav-icon fas fa-tachometer-alt',
                'name' => 'logout',
                'badge' => '',
                'option' => false
            ],
            10 => [
                'parent' => 3,
                'logo' => '',
                'name' => 'add new asset',
                'badge' => '',
                'childes' => null
            ]
        ];
        return $this->render('home/index.html.twig', [
            'aside' => $asideData
        ]);
    }

    #[Route('/ams/categories', name: 'app_categories')]
    public function categories(): Response
    {
        $categories = $this->categoriesRepository->findBy(['isDeleted' => 0]);
        $names = $this->allEntityIdsAndNames();
        $childes = [];
        foreach ($categories as $category) {
            if ($category->getParent() === null && count($category->getChildes()) > 0) {
                $childes = $names['categories'][$category->getId()];
            }
        }

        return $this->render('home/categories.html.twig', [
            'categories' => $categories,
            'subCategory' => $childes,
        ]);
    }

    #[Route('/ams/add-category', name: 'app_add_category')]
    public function addCategory(): Response
    {
        return $this->render('home/add-category.html.twig', [
            'aside' => ''
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/save-category', name: 'app_save_category')]
    public function saveCategory(Request $request): RedirectResponse
    {
        $id = $request->request->get('id');
        $id
            ? $this->categoriesMethods($this->categoriesRepository->find($id), $request, true)
            : $this->categoriesMethods(new Categories(), $request);

        return new RedirectResponse('categories');
    }
}