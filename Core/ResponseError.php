<?php


namespace Core;


use JetBrains\PhpStorm\ArrayShape;

/**
 * Class ResponseError
 * @package Core
 */
class ResponseError implements \JsonSerializable
{
    /**
     * Код ошибки
     *
     * @var int
     */
    private int $code = 500;

    /**
     * Описание ошибки
     *
     * @var string
     */
    private string $description = '';

    /**
     * ResponseError constructor.
     * @param string $description
     * @param int $code
     */
    public function __construct(string $description, int $code = 500)
    {
        $this->description = $description;
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    #[ArrayShape(['code' => "int", 'description' => "string"])] public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'description' => $this->description
        ];
    }
}