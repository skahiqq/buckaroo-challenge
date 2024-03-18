<?php

namespace App\Requests;

use App\traits\helpers\HelperTrait;

class UserRegisterRequest
{
    use HelperTrait;

    private array $data = [];

    private array $requiredFields = [
        'name',
        'email',
        'password'
    ];

    private array $messages = [];

    public function __construct($postData)
    {
        $this->data = $postData;
    }

    /**
     * @return array
     */
    public function fails(): array
    {
        $this->validateRequiredFields();
        return $this->messages;
    }

    /**
     * @return array
     */
    public function request(): array
    {
        return $this->data;
    }

    /**
     * @return void
     */
    private function validateRequiredFields(): void
    {
        foreach ($this->requiredFields as $field) {
            $_POST[$field] = $this->htmlspecialchars($_POST[$field]);

            if (empty($_POST[$field])) {
                $this->messages[$field] = "$field is required.";
            }
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function validateEmailFormat(): void
    {
        if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format.");
        }
    }
}