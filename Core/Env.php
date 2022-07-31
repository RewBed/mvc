<?php


namespace Core;

use Config;

class Env
{
    /**
     * Массив с переменными окружения
     *
     * @var array
     */
    private array $arr = [];

    public function __construct()
    {
        $envFile = Config::$basePath . '.env';

        if(file_exists($envFile)) {
            $envStr = file_get_contents($envFile);

            $envRows = explode("\n", $envStr);

            foreach ($envRows as $row) {
                $arr = explode('=', $row);
                $this->arr[$arr[0]] = $arr[1];
            }
        }
    }

    /**
     * Получить переменную
     *
     * @param string $var
     * @return string
     */
    public function get(string $var) : string {
        return $this->arr[$var] ?? '';
    }
}