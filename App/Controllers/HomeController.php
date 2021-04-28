<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Response;
use Core\ResponseError;

/**
 * Class HomeController
 * @package App\Controllers
 */
class HomeController extends BaseController
{
    /**
     * @return Response
     */
    public function index() : Response {

        $res = new Response(['demo' => 'dem']);
        $res->setHttpCode(200);
        $res->error = new ResponseError('Ошибка сохранения');

        return $res;
    }
}