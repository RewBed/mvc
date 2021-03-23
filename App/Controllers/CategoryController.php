<?php


namespace App\Controllers;


use App\Entity\Category;
use Core\BaseController;
use Core\MVC;
use Core\Responce;
use JetBrains\PhpStorm\Pure;

class CategoryController extends BaseController
{
    public function index() {

    }

    /**
     * Получить категорию
     *
     * @return Responce
     */
    #[Pure] public function item() : Responce {
        $category = new Category();
        return new Responce($category);
    }

    /**
     * Создать новую категорию
     *
     * @return array
     */
    public function create() : array {

        if(isset($this->json->id)) {
            $category = MVC::$pdo->find('categories')->where("id = $this->json->id")->get(Category::class)[0];
        }
        else {
            $category = new Category();
        }

        $category->name = $this->json->name;
        $category->alias = $this->json->alias;
        $category->parentID = $this->json->parentID;

        $category->save();

        return [];
    }
}