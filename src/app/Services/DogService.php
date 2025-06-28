<?php

namespace App\Services;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;

class DogService
{
    public function getDogs(bool $all = false): Collection
    {
        $dogs = Dog::query()->when(!$all, function ($q){
            $q->where('status', 'active');
        })
        ->where('type', 'dog')
        ->get(['id', 'name']);

        return $dogs;
    }

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
