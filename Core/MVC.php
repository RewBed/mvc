<?php


namespace Core;

use Routes\Routes;
use Config;

class MVC
{
    public static PDOWrap $pdo;

    /**
     * Инициализация MVC
     */
    public static function init() : void {

        // подключение к базе данных если нужно
        if(Config::$withDB) {
            self::dbConnect();
        }

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