<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Dog;
use App\Models\DogImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DogService
{
    private $types = [
        'dog' => 'Собака',
        'puppy' => 'Щенок',
    ];

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
        try {
            DB::beginTransaction();

            // Извлекаем специальные поля
            $description = $dogData['description'] ?? null;
            $mainImage = $dogData['image'] ?? null;
            $sliderImages = $dogData['slider_images'] ?? [];

            // Создаем основную запись собаки
            $dog = Dog::create(Arr::except($dogData, ['image', 'description', 'slider_images']));

            // Обрабатываем основное изображение
            if ($mainImage) {
                $dog->image = $this->uploadMainImage($mainImage, $dog);
                $dog->save();
            }

            // Обрабатываем описание с base64 изображениями
            if ($description) {
                $dog->description = (new ImageBase64UploadService())->saveBase64Strings(
                    $description,
                    $dog->getImagesDir()
                );
                $dog->save();
            }

            // Обрабатываем изображения для слайдера
            $this->processSliderImages($sliderImages, $dog);

            DB::commit();

            return $dog->fresh(); // Возвращаем свежую версию с отношениями

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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

    private function uploadMainImage($imageData, Dog $dog): string
    {
        return (new ImageUploadService())->uploadImage(
            $imageData,
            $dog->getImagesDir(),
            $dog->image
        );
    }

    private function processSliderImages(array $sliderImages, Dog $dog): void
    {
        if (empty($sliderImages)) {
            return;
        }

        $imageUploadService = new ImageUploadService();
        $imagesToInsert = [];

        foreach ($sliderImages as $image) {
            $fileName = $imageUploadService->uploadImage($image, $dog->getImagesDir());
            $imagesToInsert[] = [
                'dog_id' => $dog->id,
                'image' => $fileName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Массовая вставка для оптимизации
        DogImage::insert($imagesToInsert);
    }

    public function getTypeByKey(string $key): string
    {
        return $this->types[$key] ?? '';
    }
}
