<?php

namespace App\Http\Resources\Dog;

use App\Services\DogService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id' => $this->id,
            'image_link' => $this->image_link,
        ];

        return $result;
    }
}
