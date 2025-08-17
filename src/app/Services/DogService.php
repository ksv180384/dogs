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

    private $genders = [
        'male' => 'Сука',
        'female' => 'Кабель',
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
        return $this->types;
    }

    public function getGenders(): array
    {
        return $this->genders;
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
        try {
            DB::beginTransaction();

            $dog = Dog::findOrFail($id);

            // Извлекаем специальные поля
            $description = $dogData['description'] ?? null;
            $mainImage = $dogData['image'] ?? null;
            $sliderImages = $dogData['slider_images'] ?? [];
            $currentDescription = $dog->description;

            // Обрабатываем изменения в описании и изображениях
            if ($description) {
                $imagesNew = Helper::extractImageUrls($description);
                $imagesCurrent = Helper::extractImageUrls($currentDescription);
                $diffImages = array_diff($imagesCurrent, $imagesNew);

                $this->removeUnusedImages($diffImages, $dog);

                $dog->description = (new ImageBase64UploadService())->saveBase64Strings(
                    $description,
                    $dog->getImagesDir()
                );
            }

            // Обновляем основную информацию о собаке
//            dd(Arr::except($dogData, ['image', 'description']));
            $dog->update(Arr::except($dogData, ['image', 'description']));

            // Обрабатываем основное изображение
            if ($mainImage) {
                $dog->image = $this->uploadMainImage($mainImage, $dog);
                $dog->save();
            }

            // Обрабатываем изображения для слайдера
            $this->processSliderImages($sliderImages, $dog);

            DB::commit();

            return $dog->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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

    public function getTypeByKey(string $key): string
    {
        return $this->types[$key] ?? '';
    }

    public function getGenderByKey(string | null $key): string
    {
        return $this->genders[$key] ?? '';
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

    private function removeUnusedImages(array $imagesToRemove, Dog $dog): void
    {
        if (empty($imagesToRemove)) {
            return;
        }

        $imageUploadService = new ImageUploadService();

        foreach ($imagesToRemove as $image) {
            $filename = pathinfo($image, PATHINFO_BASENAME);
            $imageUploadService->deleteImage($filename, $dog->getImagesDir());
        }
    }
}
