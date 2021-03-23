<?php

namespace App\Entity;

use Core\Entity;

/**
 * Class ProductField
 * @package App\Entity
 */
class ProductField extends Entity
{
    /**
     * Наименование поля
     *
     * @var string
     */
    public string $name;

    /**
     * ID категории
     *
     * @var int
     */
    public int $categoryID;

    /**
     * Псевдоним
     *
     * @var string
     */
    public string $alias;

    /**
     * Тип поля
     *
     * @var string
     */
    public string $type;

    /**
     * Значение поумолчанию
     *
     * @var string
     */
    public string $defaultValue;
}