<?php

namespace App\Services;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

    public function create(array $dogData): Dog
    {
        $dog = Dog::query()->create($dogData);

        return $dog;
    }

    public function update(int $id, array $dogData): Dog
    {
        $dog = Dog::query()->findOrFail($id);
        $imageData = $dogData['image'];
        unset($dogData['image']); // Удаляем элемент 'image' из массива

        $dog->update($dogData);

        $imageUploadService = new ImageUploadService();
        $fileName = $imageUploadService->uploadImage(
            $imageData,
            $dog->getImagesDir(),
            $dog->image
        );

        $dog->image = $fileName;
        $dog->save();

        return $dog;
    }

    public function deleteImage(int $id): Dog
    {
        $dog = Dog::query()->findOrFail($id);
        $imageUploadService = new ImageUploadService();
        $imageUploadService->deleteImage($dog->image, $dog->getImagesDir());

        $dog->image = null;
        $dog->save();

        return $dog;
    }
}
