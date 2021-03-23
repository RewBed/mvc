<?php

namespace App\Controllers;

use App\Services\ProductService;
use Core\BaseController;
use Core\MVC;
use App\Entity\Product;
use JetBrains\PhpStorm\ArrayShape;

class HomeController extends BaseController
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * @return array
     */
    #[ArrayShape(['home' => "string"])] public function index() : array {
        return $this->productService->getAll();
    }

    /**
     * Добавить категорию
     *
     * @return array
     */
    public function addCategory() : array {

    }

    /**
     * Добавить новый продукт
     *
     * @return array
     */
    public function addProduct() : array {

    }

    /**
     * Создать поле для категории
     *
     * @return array
     */
    public function addCategoryField() : array {

    }
}