<?php

namespace App\Requests;

class UserLoginRequest
{
    private array $data;

    private array $requiredFields = [
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
        return $this->validateRequiredFields();
    }

    /**
     * @return array
     */
    private function validateRequiredFields(): array
    {
        foreach ($this->requiredFields as $field) {
            if (empty($this->data[$field])) {
                $this->messages[$field] = "$field is required.";
            }
        }

        $isEmail = $this->validateEmailFormat();

        if ($isEmail !== null) {
            $this->messages['email'] = $isEmail;
        }

        return $this->messages;
    }

    /**
     * @return string|null
     */
    private function validateEmailFormat(): ?string
    {
        if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL) && !empty($this->data['email'])) {
            return 'Email format is not correct!';
        }

        return null;
    }

    /**
     * @return array
     */
    public function request(): array
    {
        return $this->data;
    }
}