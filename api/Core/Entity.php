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
     * Сохранять время создания и обновления в таблицу
     *
     * @var bool
     */
    protected bool $withoutTime = false;


    /**
     * Entity constructor.
     * @param int $id
     */
    public function __construct(int $id = 0)
    {
        if($id) {
            $row = MVC::$pdo->getSingle([], ['id' => $id], $this->table);
            $this->setRow($row);
        }
    }

    public function setRow(array $row) {
        if(!empty($row)) {
            foreach ($row as $key => $value) {
                if(property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function setRowStd(\stdClass $row) {
        if(!empty($row)) {
            foreach ($row as $key => $value) {
                if(property_exists($this, $key) && $key !== 'updateTime' && $key !== 'createTime') {
                    $this->{$key} = $value;
                }
            }
        }
    }

    /**
     * Сохранить строку в базу
     */
    public function save() {

        // свйоства для колонки
        $data = [];

        // время создания и время обновления записи
        if(!$this->withoutTime) {
            $this->updateTime = time();
            if(!$this->id)
                $this->createTime = time();
        }

        // заполнение свойств
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($props as $key => $prop) {
            if($prop->name === 'createTime' || $prop->name === 'updateTime') {
                if(!$this->withoutTime) {
                    $data[$prop->name] = $this->{$prop->name};
                }
            }
            else {
                if(!empty($this->{$prop->name})) {
                    $data[$prop->name] = $this->{$prop->name};
                }
            }
        }

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