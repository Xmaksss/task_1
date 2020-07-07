<?php

namespace App\Libs;

use App\Libs\DBConnections\SqliteConnection;

/**
 * Class DB
 * @package App\Libs
 */
class DB
{
    private $_config;
    private $_connection;

    public function __construct(array $config)
    {
        $this->_config = $config;

        switch ($config['driver']) {
            case 'sqlite':
                $this->_connection = (new SqliteConnection($config['host']))->connect();
                break;
            case 'mysql':
                throw new \Exception('Not implemented');
                break;
            case 'pgsql';
                throw new \Exception('Not implemented');
                break;
            default:
                throw new \Exception("Undefined driver: {$config['driver']}");
        }
    }

    public function upMigrations()
    {
        /** @var \MigrationInterface[] $migrations */
        $migrations = $this->_config['migrations'] ?? [];

        $this->_connection->beginTransaction();

        foreach ($migrations as $migrationClass) {
            try {
                $migration = new $migrationClass();
                $sql = $migration->up();

                if ($this->_connection->exec($sql) === false) {
                    throw new \Exception(json_encode($this->_connection->errorInfo()));
                }

            } catch (\Exception $exception) {
                $this->_connection->rollBack();
                throw new \Exception($exception->getMessage());
            }
        }

        $this->_connection->commit();
    }

    public function freshMigrations()
    {
        // TODO implement db fresh
    }

    public function getConnection()
    {
        return $this->_connection;
    }
}