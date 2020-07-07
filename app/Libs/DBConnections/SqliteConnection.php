<?php

namespace App\Libs\DBConnections;

/**
 * Class SqliteConnection
 * @package App\Libs\DBConnections
 */
class SqliteConnection implements ConnectionInterface
{
    /** @var \PDO */
    private $_connection;

    /** @var string  */
    private $_path;

    public function __construct(string $path)
    {
        $this->_path = root_path('/database/' . ltrim($path, '/'));
    }

    public function connect()
    {
        if ($this->_connection == null) {
            $this->_connection = new \PDO("sqlite:" . $this->_path);
        }

        return $this->_connection;
    }
}