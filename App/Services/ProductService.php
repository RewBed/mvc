<?php

namespace App\Services;

use App\Entity\Product;
use Core\MVC;

class ProductService
{
    public function getAll() : array {
        return MVC::$pdo->find('products')
            ->leftJoin('fields', 'categoryID', 'categoryID', 'fieldFirst')
            ->limit(100)
            ->get(Product::class);
    }
}