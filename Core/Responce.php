<?php


namespace Core;


use JetBrains\PhpStorm\ArrayShape;

class Responce
{
    /**
     * Описание ошибки
     *
     * @var array
     */
    public array $error = [
        'isError' => false,
        'code' => 500,
        'text' => ''
    ];

    /**
     * Содержимое ответа
     *
     * @var mixed
     */
    public mixed $res;

    public function __construct(mixed $res)
    {
        $this->res = $res;
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