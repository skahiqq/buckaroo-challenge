<?php

namespace App\Controllers;

use App\Models\User;
use App\Requests\UserLoginRequest;

class LoginController extends Controller
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

        $this->render('templates/auth/login');
    }

    /**
     * Attempt to login
     * @return void
     */
    public function login()
    {
        // get validator class
        $validator = new UserLoginRequest($_POST);

        // start session
        session_start();

        // count if validator failed
        if (count($validator->fails()) > 0) {

            // set session errors fail messages
            $_SESSION['errors'] = $validator->fails();

            // save old values of inputs
            $_SESSION['old'] = [
                'email' => $_POST['email']
            ];

            // redirect to /login
            header('Location: /login');
            exit;
        }

        // get fields
        $fields = $validator->request();

        // check if we have a user with this email
        $user = $this->userModel->getByEmail($fields['email']);

        // if we have user
        if ($user) {
            // if password is correct
            if (password_verify((string)$fields['password'], $user['password'])) {
                // redirect to /home
                $_SESSION['user_id'] = $user['id'];
                header('Location: /home');
                exit;
            }
        }

        // set session errors user does not exist
        $_SESSION['errors'] = [
            'User does not exist'
        ];

        // redirect to /login
        header('Location: /login');
    }
}