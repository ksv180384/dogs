<?php

namespace App\Services;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function uploadImage($imageFile, string $directory, ?string $oldImage = null): string
    {
        $manager = new ImageManager(
            new Driver()
        );
        $img = $manager->read($imageFile);

        $fileName = time() . '.' . $imageFile->getClientOriginalExtension();

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0755, true);
        }

        if ($oldImage) {
            Storage::delete($directory . $oldImage);
        }

        $img->save(Storage::path($directory . $fileName));

        return $fileName;
    }

    public function deleteImage(string $fileName, string $directory): void
    {
        Storage::delete($directory. '/' .$fileName);
    }
}
