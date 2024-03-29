<?php

namespace App\Controllers;

use Core\BaseController;
use Core\MVC;
use Core\ResponseError;
use Redis;

/**
 * Class HomeController
 * @package App\Controllers
 */
class HomeController extends BaseController
{
    /**
     * Пример ответа
     */
    public function index() : void {
        MVC::$response->setRes('MVC 1.0');
        MVC::$response->send();
    }

    /**
     * Пример вывода ошибки
     */
    public function errorExample() : void {
        MVC::$response->setError(new ResponseError('Какой-то текст ошибки'));
        MVC::$response->send();
    }

    /**
     * Пример вывода ошибки
     */
    public function errorExampleSecond() : void {
        MVC::$response->sendError(new ResponseError('Какой-то текст ошибки, второй вариант'));
    }

    /**
     * Пример положительного ответа
     */
    public function resExample() : void {
        MVC::$response->sendRes([1, 2, 3, 4, 5]);
    }

    /**
     * Пример положительного ответа
     */
    public function resExampleSecond() : void {
        MVC::$response->sendRes('Ответ строкой');
    }

    /**
     * Информация об API
     *
     * @return void
     */
    public function info() : void {
        MVC::$response->sendRes([
            'api' => 'v1'
        ]);
    }
}