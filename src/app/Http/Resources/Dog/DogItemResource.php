<?php

namespace App\Http\Resources\Dog;

use App\Services\DogService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DogItemResource extends JsonResource
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
            'name' => $this->name,
            'gender' => (new DogService())->getGenderByKey($this->gender),
            'image_link' => $this->image_link,
            'birthdate' => $this->birthdate->format('d.m.Y'),
            'type' => (new DogService())->getTypeByKey($this->type),
            'status' => $this->status,
        ];

        return $result;
    }
}
