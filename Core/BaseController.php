<?php


namespace Core;

use stdClass;

/**
 * Class BaseController
 * @package Core
 */
class BaseController
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
     * Вернет отрендаренный шаблон
     * @param $template string
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
}