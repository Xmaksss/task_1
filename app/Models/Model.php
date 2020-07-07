<?php

namespace App\Models;

abstract class Model
{
    /** @var \PDO */
    private static $_connection;

    protected static $tableName;
    protected $fillable = [];
    private $_attributes = [];

    /**
     * @param \PDO $connection
     */
    public static function setConnection($connection)
    {
        self::$_connection = $connection;
    }

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->_attributes[$key] = $value;
            }
        }
    }

    public function save()
    {
        $this->checkConnection();

        $params = array_keys($this->_attributes);

        $paramsNames = array_map(function($v) {
            return ":$v";
        }, $params);

        if ($this->isNewRecord()) {
            $query = "INSERT INTO " . static::getTableName();
            $query .= ' (' . implode(',', $params) . ')';
            $query .= " VALUES ";
            $query .= '(' . implode(',', $paramsNames) . ')';
        } else {
            $query = "UPDATE " . static::getTableName();
            $query .= " SET ";
            $values = [];
            foreach ($params as $param) $values[] = "$param = :$param";
            $query .= implode(', ', $values);
            $query .= " WHERE id = {$this->_attributes['id']}";
        }

        $stmt = self::$_connection->prepare($query);

        if ($stmt === false)
            throw new \Exception(json_encode(self::$_connection->errorInfo()));

        $values = [];
        foreach ($this->_attributes as $attribute => $value)
            $values[":$attribute"] = $value;

        $status = $stmt->execute($values);

        if ($status && $this->isNewRecord())
            $this->setId(self::$_connection->lastInsertId());

        return $status;
    }

    public function update(array $values)
    {
        foreach ($values as $key => $value) {
            if (in_array($key, array_values($this->fillable))) {
                $this->_attributes[$key] = $values;
            }
        }

        $this->save();
    }

    /**
     * @param array $where
     * @param int $offset
     * @param int $limit
     * @return static[]
     * @throws \Exception
     */
    public static function get(array $where = [], int $offset = 0, int $limit = 50)
    {
        $query = "SELECT * FROM " . static::getTableName();
        $values = [];

        if (!empty($where)) {
            $whereStatement = [];
            foreach (array_keys($where) as $key) $whereStatement[] = "$key = :$key";
            $query .= ' WHERE ' . implode('AND', $whereStatement);

            foreach ($where as $attribute => $value)
                $values[":$attribute"] = $value;
        }
        $query .= " LIMIT $offset, $limit";

        $stmt = self::$_connection->prepare($query);

        $stmt->execute($values);

        if ($stmt === false)
            throw new \Exception(json_encode(self::$_connection->errorInfo()));

        $items = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $items[] = static::createInstance($row);
        }

        return $items;
    }

    /**
     * @param array $where
     * @return static|null
     * @throws \Exception
     */
    public static function first(array $where = [])
    {
        $rows = self::get($where, 0, 1);

        return $rows[0] ?? null;
    }

    public function isNewRecord()
    {
        return !in_array('id', array_keys($this->_attributes));
    }

    public function checkConnection()
    {
        if (!self::$_connection) {
            throw new \Exception("No connection set");
        }
    }

    public static function getTableName()
    {
        if (!empty(static::$tableName))
            return static::$tableName;
        else
            // TODO improve
            return basename(static::class) . 's';

    }

    private function setId(int $id)
    {
        $this->_attributes['id'] = $id;
        return $this;
    }

    private static function createInstance(array $data)
    {
        $instance = new static($data);
        return $instance->setId($data['id'] ?? null);
    }

    public function __get($name)
    {
        return $this->_attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->fillable)) {
            $this->_attributes[$name] = $value;
        }
    }
}