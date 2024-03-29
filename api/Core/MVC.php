<?php


namespace Core;

use Routes\Routes;
use Config;
use Redis;

class MVC
{
    public static PDOWrap $pdo;
    public static Redis $redis;
    public static Env $env;

    /**
     * Объект для формирования ответа
     *
     * @var Response
     */
    public static Response $response;

    /**
     * Инициализация MVC
     */
    public static function init() : void {

        // подключение к базе данных если нужно
        if(Config::$withDB) {
            self::dbConnect();
        }

        // инициализация объекта ответа
        self::$response = new Response();

        // переменные окружения
        self::$env = new Env();

        // запуск роутов
        Routes::execute();
    }

    /**
     * Подключение к базе данных
     */
    public static function dbConnect() : void {
        self::$pdo = new PDOWrap();
    }
}