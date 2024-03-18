<?php

namespace App\Services;

use App\Models\User;

class Auth
{
    private static $user = null;
    private static $userModel;

    public function __construct()
    {
        session_start();
        $this->userModel = new User();
    }

    /**
     * @return User
     */
    public static function getUserModel(): User
    {
        return self::$userModel;
    }

    /**
     * get authenticated user or null
     * @return User|null
     */
    public static function user(): User|null
    {
        if (isset($_SESSION['user_id'])) {
            self::$user = self::getUserModel()->getById($_SESSION['user_id']);
        }
        return self::$user;
    }
}