<?php

namespace App\MiddleWares;

use Core\MiddleWare;
use Core\Response;

class TestMiddle implements MiddleWare {

    public function init(): bool
    {
        return true;
    }

    public function break()
    {
        // TODO: Implement break() method.
    }
}