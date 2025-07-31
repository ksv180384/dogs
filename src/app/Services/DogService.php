<?php

namespace App\Services;

use App\Helpers\Helper;
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

    public function create(array $dogData): Dog
    {

        $description = $dogData['description'];
        $imageData = $dogData['image'];

        unset($dogData['image']);
        unset($dogData['description']);

        $dog = Dog::query()->create($dogData);
        $imageUploadService = new ImageUploadService();
        $fileName = $imageUploadService->uploadImage(
            $imageData,
            $dog->getImagesDir(),
            $dog->image
        );

        $description = (new ImageBase64UploadService())->saveBase64Strings($description, $dog->getImagesDir());

        $dog->image = $fileName;
        $dog->description = $description;
        $dog->save();

        return $dog;
    }

    public function update(int $id, array $dogData): Dog
    {
        $dog = Dog::query()->findOrFail($id);
        $imageData = $dogData['image'];
        $description = $dogData['description'];
        $currentDescription = $dog->description;

        unset($dogData['image']); // Удаляем элемент 'image' из массива
        unset($dogData['description']);

        $imagesNew = Helper::extractImageUrls($description);
        $imagesCurrent = Helper::extractImageUrls($currentDescription);
        $diffImages = array_diff($imagesCurrent, $imagesNew);

        $imageUploadService = new ImageUploadService();

        if($diffImages){
            foreach ($diffImages as $imageNeedRemove){
                $filenameNeedRemove = pathinfo($imageNeedRemove, PATHINFO_BASENAME);
                $imageUploadService->deleteImage($filenameNeedRemove, $dog->getImagesDir());
            }
        }

        $dog->update($dogData);

        if($imageData){
            $fileName = $imageUploadService->uploadImage(
                $imageData,
                $dog->getImagesDir(),
                $dog->image
            );
            $dog->image = $fileName;
        }

        $description = (new ImageBase64UploadService())->saveBase64Strings($description, $dog->getImagesDir());

        $dog->description = $description;
        $dog->save();

        // todo проверка
        //ыфвфы
        ///фывфывфыв
        /// фывфывфыв
        /// фывфывфыв
        /// фывфы
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
