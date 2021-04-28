<?php


namespace Core;
use Core\Response;

interface MiddleWare
{
    public function init() : bool;
    public function break() : Response;
}