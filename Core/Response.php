<?php


namespace Core;


use JetBrains\PhpStorm\ArrayShape;
use Core\ResponseError;
use JetBrains\PhpStorm\Pure;

class Response
{
    /**
     * Описание ошибки
     *
     * @var ResponseError
     */
    public ResponseError $error;

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
     * Вывод ответа
     *
     * @return array
     */
    #[ArrayShape(['error' => "array", 'res' => "mixed"])] public function get() : array {
        return [
            'error' => $this->error,
            'res' => $this->res
        ];
    }
}