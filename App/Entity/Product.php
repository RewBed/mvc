<?php

namespace App\Entity;

use Core\Entity;

/**
 * Class Product
 * @package App\Entity
 */
class Product extends Entity
{
    /**
     * Наименование товара
     *
     * @var int
     */
    public int $name;

    /**
     * ID категории товара
     *
     * @var int
     */
    public int $categoryID;

    /**
     * Список полей товара
     *
     * @var array
     */
    private array $fields = [];
}