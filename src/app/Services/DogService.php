<?php

namespace App\Services;

class DogService
{
    public function getTypes(): array
    {
        return [
            'dog' => 'Собака',
            'puppy' => 'Щенок'
        ];
    }

    public function getStatuses(): array
    {
        return [
            'active' => 'Активен',
            'hidden' => 'Скрыт'
        ];
    }
}
