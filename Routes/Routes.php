<?php

namespace Routes;


use App\Controllers\HomeController;
use App\MiddleWares\TestMiddle;

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
        Route::route('/', HomeController::class)->middleWare(TestMiddle::class);

        Route::execute();
    }
}
