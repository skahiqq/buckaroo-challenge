<?php

namespace App\Controllers;

use App\DbConnection;
use App\Models\User;
use App\Requests\UserRegisterRequest;
use http\Header;

class RegisterController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Show index
     * @return void
     */
    public function index()
    {
        session_start();
        if (isset($_SESSION['user_id'])) {
            header('Location: /home');
            exit;
        }
        $this->render('templates/auth/register', []);
    }

    /**
     * Register new user
     * @return void
     */
    public function store()
    {
        // get validator class
        $validator = new UserRegisterRequest($_POST);
        // start session
        session_start();
        // if validator fails
        if (count($validator->fails()) > 0) {
            // set session errors fail messages
            $_SESSION['errors'] = $validator->fails();

            // save old values of inputs
            $_SESSION['old'] = [
                'email' => $_POST['email'],
                'name' => $_POST['name']
            ];

            // redirect to /register
            header('Location: /register');
            exit;
        }
        // clear sessions variables
        $_SESSION = array();

        $pdo = $this->userModel->create($validator->request());

        // set session user_id
         $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: /home');
        exit;
    }
}