<?php


namespace Core;


use Core\ResponseError;

class Response
{
    /**
     * Описание ошибки
     *
     * @var ResponseError
     */
    private ResponseError $error;

    /**
     * Содержимое ответа
     *
     * @var mixed
     */
    private mixed $res;

    #[Pure] public function __construct(mixed $res = [])
    {
        $this->res = $res;
    }

    /**
     * Установить HTTP код
     *
     * @param int $code
     */
    public function setHttpCode(int $code) : void {
        http_response_code($code);
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
     * Разрешить кроссдоменные запросы
     */
    public function accessCors() : void {
        Header('Access-Control-Allow-Origin: *');
        Header('Access-Control-Allow-Headers: *');
        Header('Access-Control-Allow-Methods: *');
    }

    /**
     * Установить значение ошибки
     *
     * @param ResponseError $error
     */
    public function setError(ResponseError $error) : void {
        $this->error = $error;
    }

    /**
     * Установить значение для ответа
     *
     * @param mixed $res
     */
    public function setRes(mixed $res) : void {
        $this->res = $res;
    }

    /**
     * Отправить ответ
     */
    public function send() : void {

        $data = [];

        if(isset($this->res))
            $data['res'] = $this->res;

        if(isset($this->error)) {
            $this->setHttpCode($this->error->getCode());
            $data['error'] = $this->error;
        }


        $this->setHeader('Content-Type', 'application/json; charset=utf-8');

        print json_encode($data);
    }

    /**
     * Отправить положительный ответ
     *
     * @param mixed $res
     */
    public function sendRes(mixed $res) : void {
        $this->res = $res;
        $this->send();
    }

    /**
     * Отправить ошибку
     *
     * @param \Core\ResponseError $error
     */
    public function sendError(ResponseError $error) : void {
        $this->error = $error;
        $this->send();
    }
}