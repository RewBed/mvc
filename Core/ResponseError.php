<?php


namespace Core;


/**
 * Class ResponseError
 * @package Core
 */
class ResponseError
{
    /**
     * Код ошибки
     *
     * @var int
     */
    public int $code = 500;

    /**
     * Описание ошибки
     *
     * @var string
     */
    public string $description = '';

    /**
     * Имя ошибки
     *
     * @var string
     */
    public string $name = 'Error';

    public function __construct(string $description)
    {
        $this->description = $description;
    }
}