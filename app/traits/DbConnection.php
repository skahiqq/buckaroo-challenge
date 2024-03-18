<?php

namespace App\traits;
use \PDO;
const DB_HOST = 'db:3306';
const DB_USER = 'root';
const DB_PASSWORD = 'root';
const DB_DATABASE = 'db';

trait DbConnection
{
    public $connection = null;

    /**
     * create db connection
     */
    public function __construct()
    {
        try {
            $this->connection = new \PDO("mysql:host=".DB_HOST.";dbname=db", DB_USER, DB_PASSWORD);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * @return mixed|PDO|null
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $query
     * @return mixed|null
     */
    public function dbQuery(string $query)
    {
        $statement = $this->getConnection()->query($query)->fetch();
        if ($statement) {
            return $statement;
        }
        return null;
    }

    /**
     * @param string $query
     * @param array $fields
     * @return mixed|PDO|null
     */
    public function insertQuery(string $query, array $fields)
    {
        $pdo = $this->getConnection();
        $statement = $pdo->prepare($query);
        $statement->execute($fields);

        return $pdo;
    }
}

?>