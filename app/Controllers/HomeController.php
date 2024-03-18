<?php
namespace App\Controllers;

use App\Models\User;
use App\traits\Mafia;

class HomeController extends Controller
{
    use Mafia;

    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function index()
    {
        session_start();
        $user = null;

        if (isset($_SESSION['user_id'])) {
            $user = $this->userModel->getById($_SESSION['user_id']);

            $this->render('templates/home', [
                'user' => $user,
                'role' => $this->getRandomRole(),
                'roles' => $this->getRoleKeys()
            ]);
            exit;
        }

        header('Location: /login');
    }
}