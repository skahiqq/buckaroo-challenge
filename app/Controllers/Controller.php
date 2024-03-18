<?php
namespace App\Controllers;

class Controller {
    /**
     * @param string $view
     * @param array $data
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        include "resources/views/$view.php";
    }
}