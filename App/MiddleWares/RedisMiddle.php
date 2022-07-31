<?php


namespace App\MiddleWares;


use Core\MiddleWare;
use Core\MVC;
use Redis;

class RedisMiddle implements MiddleWare
{
    public function init(): bool
    {
        MVC::$redis = new Redis();

        /*var_dump(MVC::$env->get('REDIS_HOST'));
        var_dump(MVC::$env->get('REDIS_PORT'));
        var_dump(MVC::$env->get('REDIS_PASSWORD'));*/

        // подключаемся к серверу redis
        MVC::$redis->connect('redis', MVC::$env->get('REDIS_PORT'));

        // авторизуемся. 'eustatos' - пароль, который мы задали в файле `.env`
        MVC::$redis->auth('eustatos');

        return true;
    }

    public function break()
    {
        // TODO: Implement break() method.
    }
}