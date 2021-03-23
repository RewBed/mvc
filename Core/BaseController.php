<?php


namespace Core;

use http\Header;
use JetBrains\PhpStorm\ArrayShape;
use stdClass;

/**
 * Class BaseController
 * @package App
 */
abstract class BaseController
{

    public array $post;
    public stdClass $json;
    public array $headers = [];

    /**
     * Папка с шаблонами
     * @var string
     */
    private string $templateFolder = '/App/Views/';

    /**
     * Формат шаблонов
     * @var string
     */
    private string $templateFormat = '.tpl';

    /**
     * Сообщение если шаблон не сущесвует
     * @var string
     */
    private string $templateNotFound = 'View not found';

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        if(!empty($_POST))
            $this->post = $_POST;
        else {
            $json = file_get_contents('php://input');
            if($json) {
                $this->json = json_decode($json);
            }
        }

        $this->headers = getallheaders();
        $_POST = [];
    }

    /**
     * Разрешить кроссдоменные запросы
     */
    public function accessCors() : void {
        Header('Access-Control-Allow-Origin: *');
        Header('Access-Control-Allow-Headers: *');
        Header('Access-Control-Allow-Methods: *');
    }

    /**
     * Вернет отрендаренный шаблон
     * @param $template
     * @param $data array
     * @return string
     */
    public function view(string $template, array $data = []) : string {

        $file = \Config::$basePath . $this->templateFolder . $template . $this->templateFormat;

        if(file_exists($file)) {
            extract($data);
            unset($data);
            ob_start();
            include($file);
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }
        return $this->templateNotFound;
    }

    /**
     * Задать заголовок
     *
     * @param string $headerName
     * @param string $val
     */
    public function setHeader(string $headerName, string $val) : void {
        Header($headerName . ': '. $val);
    }

    /**
     * Вывод ошибки
     *
     * @param int $errorCode
     * @param string $str
     */
    public function returnError(int $errorCode, string $str) : void {
        $arr = [
            'code' => $errorCode,
            'error' => $str
        ];

        print json_encode($arr);
        exit;
    }

    /**
     * вывод стандартных ошибок
     *
     * @param int $code
     */
    public function defaultError(int $code) : void {
        switch ($code) {
            case 401: $this->returnError($code, '403 Forbidden');
        }
    }
}