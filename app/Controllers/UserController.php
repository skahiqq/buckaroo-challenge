<?php

namespace App\Controllers;

use App\DbConnection;
use App\Requests\UserLoginRequest;
use App\Requests\UserRegisterRequest;
use http\Header;
use JetBrains\PhpStorm\NoReturn;

class UserController extends Controller
{
    public function login()
    {
        $validator = new UserLoginRequest($_POST);

        if (count($validator->fails()) > 0) {
           /* session_start();
            $_SESSION['errors'] = $validator->fails();

            $_SESSION['old'] = [
                'email' => $_POST['email']
            ];*/

            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header('Location: /login');
    }

    public function register()
    {
        $validator = new UserRegisterRequest($_POST);
        session_start();
        if (count($validator->fails()) > 0) {

            var_dump("asd");
            $_SESSION['errors'] = $validator->fails();

            $_SESSION['old'] = [
                'email' => $_POST['email']
            ];

            header('Location: /register');
            exit;
        }

        $sql = "INSERT INTO users (name, email, password) VALUES (?,?,?)";
        $pdo = new DbConnection();
        $pdo = $pdo->getConnection();
        $statement = $pdo->prepare($sql);
        $statement->execute([$_POST['name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT)]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: /home');
        exit;
    }


}