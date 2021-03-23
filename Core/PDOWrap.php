<?php

namespace Core;

use Exception;
use http\Encoding\Stream\Debrotli;
use MongoDB\Driver\Server;
use PDO;
use PDOException;
use PDOStatement;


class PDOWrap
{
    private $dbh;
    private $error;
    private $errorCode;

    /** @var $stmt PDOStatement */
    private $stmt;

    /**
     * Генерация SQL запроса
     *
     * @var string
     */
    private string $sql = '';

    /**
     * Таблица для которой формируется новый запрос
     *
     * @var string
     */
    private string $currentTable = '';

    /**
     * Поля для текущего запроса
     *
     * @var string
     */
    private string $currentFields = '*';

    /**
     * Шаблон для замены списка полей
     */
    private string $SQLFIELDS = 'SQLFIELDS';

    public function __construct()
    {
        $dsn = 'mysql:host=' . \Config::$db['db_host'] . ';dbname=' . \Config::$db['db_name'];
        $options = array(
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_STRINGIFY_FETCHES  => false,
        );
        try {
            $this->dbh = new PDO($dsn, \Config::$db['db_user'], \Config::$db['db_pass'], $options);
            if (!is_null($this->dbh)) {
                $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->dbh->setAttribute(PDO::ATTR_PERSISTENT, true);
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                $this->dbh->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
                $this->dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
                $this->dbh->exec("set names utf8");
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->errorCode = $e->getCode();
            die();
        }
    }

    /**
     * Выборка строк из базы
     *
     * @param string $table
     * @return $this
     */
    public function find(string $table) : PDOWrap {
        $this->currentTable = $table;
        $this->sql = "SELECT $this->SQLFIELDS FROM " . $table;
        return $this;
    }

    /**
     * Условие
     *
     * @param string $where
     * @return $this
     */
    public function where(string $where) : PDOWrap {
        $this->sql .= " WHERE " . $where;
        return $this;
    }

    /**
     * Установить лимит на количество записей
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit) : PDOWrap {
        $this->sql .= ' LIMIT '.$limit;
        return $this;
    }

    /**
     * С какой строки начать выборку
     *
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset) : PDOWrap {
        $this->sql .= ' OFFSET '.$offset;
        return $this;
    }

    /**
     * Получить данные по запросу
     *
     * @param string $class
     * @return array
     * @throws Exception
     */
    public function get(string $class) : array {
        $this->sql = str_replace($this->SQLFIELDS, $this->currentFields, $this->sql);
        var_dump($this->sql);
        return [];
        try {
            return $this->getQuery()->fetchAll(PDO::FETCH_CLASS, $class);
        }
        catch (CustomException $e) {
            print $e->errorLog();
        }
    }

    /**
     * LEFT JOIN
     *
     * @param string $table
     * @param string $currentFilter
     * @param string $filter
     * @param string $as
     * @return PDOWrap
     */
    public function leftJoin(string $table, string $currentFilter, string $filter, string $as) : PDOWrap {
        $this->currentFields .= ", $table.$filter AS $as";
        $this->sql .= " LEFT JOIN $table ON $this->currentTable.$currentFilter = $table.$filter";
        return $this;
    }

    /**
    * Получить обработанный запрос SQL
    * Так же проверяет его валидность
    *
    * @return PDOStatement
    * @throws Exception
    */
    private function getQuery() : PDOStatement {

        $query = $this->dbh->query($this->sql);
        if($query)
            return $query;
        else
            throw new CustomException('Not valid sql: '.$this->sql);
    }

    /**
     * Вставить строку в базу
     *
     * @param array  $data
     * @param string $table
     * @return false|int
     */
    public function insert(array $data, string $table) : bool|int
    {
        $setSqlKeys = [];
        $setSqlValues = [];
        foreach ($data as $key => $value) {
            $setSqlKeys[] = "`$key`";
            $setSqlValues[$key] = ":$key";
        }
        $setStringKeys = implode(', ', $setSqlKeys);
        $setStringValues = implode(', ', $setSqlValues);
        $sql = sprintf("INSERT INTO $table (%s) VALUES (%s)", $setStringKeys, $setStringValues);
        $this->query($sql);

        try {
            foreach ($data as $key => $value) {
                $this->bind(":$key", $value);
            }
            return $this->execute() ? intval($this->lastInsertId()) : false;

        } catch (Exception $e) {

            $this->error = $e->getMessage();
            $this->errorCode = $e->getCode();
            return false;
        }

    }

    /**
     * Последний ID добавленный в базу
     *
     * @return string
     */
    public function lastInsertId() : string
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Получить только один экземпляр (одну строку)
     */
    public function findOne() {

    }

    // старые методы

    public function getError()
    {
        return $this->error;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute($params = [])
    { // execute query; returns false or true

        if (!empty($params)) {
            return $this->stmt->execute($params);
        } else {
            return $this->stmt->execute();
        }
    }

    public function resultset($params = [])
    { // select multiple rows

        if (!empty($params)) {
            $this->execute($params);
        } else {
            $this->execute();
        }
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single($params = [])
    { // select a single row
        if (!empty($params)) {
            $this->execute($params);
        } else {
            $this->execute();
        }
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    { // number of records returned;
        return $this->stmt->rowCount();
    }

    public function fetch()
    { // number of records returned;
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function beginTransaction()
    { // multiple insert;
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    { // end transaction;
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    { // cancel transaction;
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    { // dump sql query;
        return $this->stmt->debugDumpParams();
    }

    /**
     * @param array  $data
     * @param array  $where
     * @param string $table
     *
     * @return bool
     */
    public function update($data, $where, $table)
    {
        $setSql = [];
        $whereSql = [];
        foreach ($data as $key => $value) {
            $setSql[] = "`$key` = :$key";
        }
        foreach ($where as $key => $value) {
            $whereSql[] = "`$key` = :$key";
        }
        $setString = implode(', ', $setSql);
        $whereString = implode(' AND ', $whereSql);
        $sql = sprintf("UPDATE $table SET %s WHERE %s", $setString, $whereString);
        $this->query($sql);
        try {
            foreach ($data as $key => $value) {
                $this->bind(":$key", $value);
            }
            foreach ($where as $key => $value) {
                $this->bind(":$key", $value);
            }

            return $this->execute();

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->errorCode = $e->getCode();
            return false;
        }
    }

    /**
     * @param array  $data
     * @param array  $where
     * @param string $table
     * @param string $orderBy
     * @param string $order
     *
     * @return bool|mixed
     */
    public function getSingle($data, $where, $table, $orderBy = '', $order = '')
    {
        $selectString = '*';
        if (!empty($data)) {
            $setSql = [];
            foreach ($data as $key => $value) {
                if (is_numeric($key)) {

                    $setSql[] = "`{$value}`";
                } else {
                    $setSql[] = "`{$key}`";
                }
            }
            $selectString = implode(', ', $setSql);
        }


        $whereSql = [];
        foreach ($where as $key => $value) {
            if (is_null($value)) {
                $whereSql[] = "`{$key}` IS NULL";
            } else {
                $whereSql[] = "`$key` = :$key";
            }
        }
        $whereString = implode(' AND ', $whereSql);

        if (empty($orderBy)) {
            $sql = sprintf("SELECT %s FROM {$table} WHERE %s;", $selectString, $whereString);
        } else {
            if (mb_strpos($orderBy, 'STR_TO_DATE') === false) {
                $orderBy = '`' . $orderBy . '`';
            }

            $sql = sprintf("SELECT %s FROM {$table} WHERE %s ORDER BY %s %s", $selectString, $whereString, $orderBy, empty($order) ? 'ASC' : $order);
        }
        $this->query($sql);

        try {
            foreach ($where as $key => $value) {
                if (!is_null($value)) {
                    $this->bind(":$key", $value);
                }
            }

            return $this->single();

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->errorCode = $e->getCode();
            return false;
        }
    }

    /**
     * @param array|null $data
     * @param string     $table
     * @param string     $orderBy
     * @param string     $order
     *
     * @return array|bool
     */
    public function getAll($data, $table, $orderBy = '', $order = '')
    {
        $setSql = [];

        $selectString = '*';

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (is_numeric($key)) {
                    $setSql[] = "`{$value}`";
                } else {
                    $setSql[] = "`{$key}`";
                }
            }
            $selectString = implode(', ', $setSql);
        }

        if (empty($orderBy)) {
            $sql = sprintf("SELECT %s FROM {$table}", $selectString);
        } else {
            if (mb_strpos($orderBy, 'STR_TO_DATE') === false) {
                $orderBy = '`' . $orderBy . '`';
            }

            $sql = sprintf("SELECT %s FROM {$table} ORDER BY %s %s", $selectString, $orderBy, empty($order) ? 'ASC' :
                $order);
        }
        $this->query($sql);
        try {
            if (empty($orderBy)) {
                $this->bind(":orderby", $orderBy);
            }

            return $this->resultset();

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->errorCode = $e->getCode();
            return false;
        }
    }

    public function getWhere($data, $where, $table, $orderBy = '', $order = '', $groupBy = '')
    {
        $selectString = '*';
        if (!empty($data)) {
            $setSql = [];
            foreach ($data as $key => $value) {
                if (is_numeric($key)) {
                    $setSql[] = "`{$value}`";
                } else {
                    $setSql[] = "`{$key}`";
                }
            }
            $selectString = implode(', ', $setSql);
        }

        $whereString = '';
        if (!empty($where)) {
            $whereSql = [];
            foreach ($where as $key => $value) {
                if (is_null($value)) {
                    $whereSql[] = "`{$key}` IS NULL";
                } else {
                    $whereSql[] = "`$key` = :$key";
                }
            }
            $whereString = "WHERE " . implode(' AND ', $whereSql);
        }


        if (empty($orderBy)) {
            $sql = sprintf("SELECT %s FROM {$table} %s;", $selectString, $whereString);
        } else {
            $group = !empty($groupBy) ? 'GROUP BY ' . $groupBy : '';

            if (mb_strpos($orderBy, 'STR_TO_DATE') === false && mb_strpos($orderBy, ',') === false ) {
                if(mb_strpos($orderBy, ',') !== false ){
                    $ob = explode(',', $orderBy);

                    $orderBy = '`' . implode('`,`', $ob) . '`';
                }
                else{
                    $orderBy = '`' . $orderBy . '`';
                }
            }

            $sql = sprintf("SELECT %s FROM {$table} %s {$group} ORDER BY %s %s", $selectString, $whereString, $orderBy, empty($order) ?
                'ASC' : $order);
        }

        $this->query($sql);
        try {
            foreach ($where as $key => $value) {
                if (!is_null($value)) {
                    $this->bind(":$key", $value);
                }
            }

            return $this->resultset();

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->errorCode = $e->getCode();
            return false;
        }
    }

    /**
     * Удаление записи
     *
     * @param string $table
     * @param array  $where
     *
     * @return bool
     */
    public function delete($table, $where)
    {
        $whereSql = [];
        foreach ($where as $key => $value) {
            $whereSql[] = "`$key` = :$key";
        }
        $whereString = implode(' AND ', $whereSql);

        $sql = sprintf("DELETE FROM $table WHERE %s", $whereString);
        $this->query($sql);
        try {
            foreach ($where as $key => $value) {
                $this->bind(":$key", $value);
            }
            return $this->execute();

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->errorCode = $e->getCode();
            return false;
        }
    }
}