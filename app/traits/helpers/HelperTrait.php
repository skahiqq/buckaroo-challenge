<?php

namespace App\traits\helpers;

trait HelperTrait
{
    /**
     * @param string $input
     * @return string
     */
    public function htmlspecialchars(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
