<?php


namespace App\Entity;


use Core\Entity;

/**
 * Class Category
 * @package App\Entity
 */
class Category extends Entity
{

    /**
     * Таблица
     *
     * @var string
     */
    protected string $table = 'categories';

    /**
     * Название категории
     *
     * @var string
     */
    public string $name;

    /**
     * Пседомним категории
     * Будет использоваться при формировании ссылки
     *
     * @var string
     */
    public string $alias;


    /**
     * Родительская категория
     *
     * @var int
     */
    public int $parentID;
}