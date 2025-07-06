<?php

namespace App\Http\Resources\Dog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DogResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'image' => $this->image_link,
            'description' => $this->description,
            'birthdate' => $this->birthdate,
            'type' => $this->type,
            'status' => $this->status,
        ];

        return $result;
    }
}
