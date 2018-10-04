<?php

namespace App\Repositories;

use App\Support\ConnectDatabase;

/**
 * Database repository
 */
class DatabaseRepository
{
    public $dbName;

    protected $connection;
    protected $connDB;

    public function __construct($dbName = null, $connection = null)
    {
        $this->connDB = new ConnectDatabase();

        $this->dbName = $dbName;
        $this->connection = $connection;

        if (is_null($connection)) {
            $this->connection = env('DB_CONNECTION') ;
        }
    }

    public function getDatabase($dbName = null, $set = true)
    {
        if (is_null($dbName)) {
            $dbName = $this->dbName;
        }

        if (is_null($dbName)) {
            throw NullDatabaseNameException();
        }

        $database = \DB::connection($this->connection)
            ->table('dbs')
            ->where('label', $dbName)
            ->first();

        if ($set) {
            $this->setDatabase($database);
        }

        return $database;
    }

    public function unsetDatabase()
    {
        return $this->connDB
                    ->unsetDatabase($this->getDatabase()->driver);
    }

    public function getDatabaseInfo($dbName = null)
    {
        if (is_null($dbName)) {
            $dbName = $this->dbName;
        }

        if (is_null($dbName)) {
            throw NullDatabaseNameException();
        }

        $db = Dbs::select([
                    'label',
                    'driver',
                    'host',
                    'port',
                    'database',
                    'charset',
                ])
                ->where('label', $dbName)
                ->first();

        return $db;
    }

    private function setDatabase($database)
    {
        return $this->connDB
                    ->setDatabase($database->driver, $database);
    }

    public function getQuery($tableName, $dbName = null)
    {
        return \DB::connection($this->getDatabase($dbName)->driver)
                                    ->table($tableName);
    }
}
