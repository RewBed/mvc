<?php


namespace App\MiddleWares;


use Core\MiddleWare;
use Core\MVC;
use Core\Response;
use Redis;

class RedisMiddle implements MiddleWare
{

    public function init(): bool
    {
        // TODO: Implement init() method.
        MVC::$redis = new Redis();

        // подключаемся к серверу redis
        MVC::$redis->connect('redis', 6379);

        // авторизуемся. 'eustatos' - пароль, который мы задали в файле `.env`
        MVC::$redis->auth('eustatos');

        return true;
    }

    public function break()
    {
        // TODO: Implement break() method.
    }
}