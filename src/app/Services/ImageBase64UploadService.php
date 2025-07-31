<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageBase64UploadService
{
    public function uploadImage($imageFileBase64, string $directory): string
    {
        $manager = new ImageManager(
            new Driver()
        );
        // Проверяем формат и извлекаем MIME-тип
        if (!preg_match('/^data:image\/(\w+);base64,/', $imageFileBase64, $matches)) {
            throw new Exception('Invalid base64 image format');
        }
        $extension = $matches[1]; // Получаем расширение (png, jpeg, gif и т. д.)

        $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageFileBase64));
        if (!$imageData) {
            throw new Exception('Failed to decode base64 image');
        }

        // Создаем изображение
        $image = $manager->read($imageData);

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0755, true);
        }

        // Формируем имя файла с оригинальным расширением
        $filename = $directory . Str::random(6) . '.' . $extension; // или любое другое имя с этим расширением

        // Если нужно указать качество (для JPEG/WEBP)
        if (in_array($extension, ['jpeg', 'jpg', 'webp'])) {
            $image->save(Storage::path($filename), 90); // качество 90%
        } else {
            $image->save(Storage::path($filename)); // PNG, GIF не поддерживают качество
        }

        return $filename;
    }

    public function deleteImage(string $fileName, string $directory): void
    {
        Storage::delete($directory. '/' .$fileName);
    }

    public function saveBase64Strings(string $text, string $directory): string
    {
        $pattern = '/data:\w+\/[^;]+;base64,[A-Za-z0-9+\/]+={0,2}/';
        preg_match_all($pattern, $text, $matches);
        $imagesList = $matches[0] ?? [];

        if(!$imagesList){
            return $text;
        }

        $imagesPaths = $this->saveBase64ImagesFromArray($imagesList, $directory);

        $modifiedText = $text;
        foreach ($imagesPaths as $index => $path) {
            if (isset($imagesList[$index])) {
                $modifiedText = str_replace($imagesList[$index], $path, $modifiedText);
            }
        }

        return $modifiedText;
    }

    function saveBase64ImagesFromArray(array $arImagesBase64, string $directory): array
    {

        $savedPaths = [];

        foreach ($arImagesBase64 as $image) {

            $savedPaths[] = '/storage/' . $this->uploadImage($image, $directory);
        }

        return $savedPaths;
    }
}
