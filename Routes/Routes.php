<?php

namespace Routes;


use App\Controllers\HomeController;
use App\Controllers\CategoryController;

/**
 * Class Routes
 * @package App\Routes
 */
class Routes
{

    /**
     * Список роутов
     */
    public static function execute() : void {

        /** API */
        Route::route('/', HomeController::class);
        Route::route('/api/category/save', CategoryController::class, 'create');

        Route::execute();
    }
}
