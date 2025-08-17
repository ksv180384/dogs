<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Dog;
use App\Models\DogImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DogImageService
{


    public function create(array $image, Dog $dog): DogImage | null
    {
        if (empty($image)) {
            return null;
        }

        $imageUploadService = new ImageUploadService();
        $fileName = $imageUploadService->uploadImage($image, $dog->getImagesDir());

        $dogImage = DogImage::create([
            'dog_id' => $dog->id,
            'image' => $fileName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $dogImage;
    }

    public function update(int $id, array $image, Dog $dog): DogImage | null
    {
        if (empty($image)) {
            return null;
        }

        $imageUploadService = new ImageUploadService();
        $fileName = $imageUploadService->uploadImage($image, $dog->getImagesDir());

        $dogImage = DogImage::query()->findOrFail($id);
        $dogImage->update([
            'dog_id' => $dog->id,
            'image' => $fileName,
            'updated_at' => now(),
        ]);

        return $dogImage;
    }


    public function delete(int $id): void
    {
        $dogImage = DogImage::query()->findOrFail($id);

        $imageUploadService = new ImageUploadService();
        $imageUploadService->deleteImage($dogImage->image, $dogImage->getImagesDir());

        $dogImage->delete();
    }
}
