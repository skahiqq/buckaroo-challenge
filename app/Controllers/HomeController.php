<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\Auth;
use App\Services\Middleware;
use App\traits\Mafia;

class HomeController extends Controller
{
    use Mafia;

    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        session_start();
        $user = null;

        if (isset($_SESSION['user_id'])) {
            $user = $this->userModel->getById($_SESSION['user_id']);

            $this->render('templates/home', [
                'user' => $user,
                'role' => $this->getRandomRole(),//'Detective'
                'roles' => $this->getRoleKeys()
            ]);
            exit;
        }

        header('Location: /login');
    }
}