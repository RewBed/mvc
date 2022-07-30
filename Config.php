<?php

/**
 * Конфигурация проекта
 * Class Config
 */
class Config
{

    /**
     * Настройки для подключения к базе данных
     *
     * @var array[]
     */
    public static array $db = [
        'db_host' => 'mariadb',
        'db_user' => 'root',
        'db_pass' => 'secret',
        'db_name' => 'mvc'
    ];

    /**
     * Нужно ли подключаться к базе данных
     *
     * @var bool
     */
    public static bool $withDB = true;

    /**
     * Путь до папки проекта
     *
     * @var string
     */
    public static string $basePath = __DIR__ . '/';

}
