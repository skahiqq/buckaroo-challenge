<?php

namespace App;
use PDO;
const DB_HOST = 'db:3306';
const DB_USER = 'root';
const DB_PASSWORD = 'root';
const DB_DATABASE = 'db';

class DbConnection
{
    public $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=".DB_HOST.";dbname=db", DB_USER, DB_PASSWORD);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function table(string $table, string $query)
    {
        return $this->getConnection()->query($query);
    }
}

?>