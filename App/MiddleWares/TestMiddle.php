<?php

namespace App\MiddleWares;

use Core\MiddleWare;
use Core\Response;
use Core\ResponseError;
use JetBrains\PhpStorm\Pure;

class TestMiddle implements MiddleWare {

    public function init(): bool
    {
        return true;
    }

    #[Pure] public function break(): Response
    {
        $res = new Response();
        $res->error = new ResponseError('Ошибка в тест мидлейвр');
        return $res;
    }
}