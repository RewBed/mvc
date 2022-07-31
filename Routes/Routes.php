<?php

namespace Routes;


use App\Controllers\HomeController;
use App\MiddleWares\RedisMiddle;
use App\MiddleWares\TestMiddle;
use App\Controllers\SensorReadingController;


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
        Route::route('/error', HomeController::class, 'errorExample');
        Route::route('/res-example', HomeController::class, 'resExample');
        Route::route('/error-example-second', HomeController::class, 'errorExampleSecond');
        Route::route('/res-example-second', HomeController::class, 'resExampleSecond');
        Route::route('/api/sensor-reading/add', SensorReadingController::class, 'set')->middleWare(RedisMiddle::class);
        Route::route('/api/sensor-reading/list', SensorReadingController::class, 'list');

        Route::execute();
    }
}
