<?php

namespace App\Models;

use App\traits\DbConnection;
use App\traits\helpers\HelperTrait;

class User
{
    use HelperTrait;
    use DbConnection;

    // fields
    protected $fillables = [
        'name',
        'email',
        'password'
    ];

    /**
     * get user by email
     * @param string $email
     * @return mixed|null
     */
    public function getByEmail(string $email): mixed
    {
        return $this->dbQuery("SELECT * FROM users where email = '" . $email . "'");
    }

    /**
     * get user by id
     * @param int $id
     * @return mixed|null
     */
    public function getById(int $id): mixed
    {
        return $this->dbQuery('SELECT * FROM users where id = ' . $id);
    }

    /**
     * create new user
     * @param $fields
     * @return mixed|\PDO|null
     */
    public function create($fields): mixed
    {
        $today = date("Y-m-d h:i");
        $query = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?,?,?,?,?)";
        return $this->insertQuery($query, [$this->htmlspecialchars($fields['name']), $this->htmlspecialchars($fields['email']), password_hash($fields['password'], PASSWORD_BCRYPT), $today, $today]);
    }
}