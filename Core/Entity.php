<?php


namespace Core;


use ReflectionClass;
use ReflectionProperty;

abstract class Entity
{
    public int $id = 0;

    /**
     * Время создания строки в базе
     *
     * @var int
     */
    public int $createTime;

    /**
     * Вреся обновления строки в базе
     *
     * @var int
     */
    public int $updateTime;

    /**
     * Таблица к которой относиться модель
     *
     * @var string
     */
    protected string $table;


    /**
     * Entity constructor.
     * @param int $id
     */
    public function __construct()
    {

    }

    /**
     * Сохранить строку в базу
     */
    public function save() {

        // свйоства для колонки
        $data = [];

        // время создания и время обновления записи
        $this->updateTime = time();
        if(!$this->id)
            $this->createTime = time();

        // заполнение свойств
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($props as $key => $prop)
            $data[$prop->name] = $this->{$prop->name};

        // если это новая запись
        if(!$this->id) {
            $id = MVC::$pdo->insert($data, $this->table);
            if($id)
                $this->id = $id;
        }

        // обовить текущею запись
        else {
            MVC::$pdo->update($data, ['id' => $this->id], $this->table);
        }
    }

    public function __set(string $name, $value): void
    {
        // TODO: Implement __set() method.
    }
}