<?php

namespace App\Http\Requests\Dog;

use Illuminate\Foundation\Http\FormRequest;

class CreateDogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:dogs,id'],
            'name' => ['required'],
            'image' => ['required', 'image'],
            'description' => ['required'],
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'type' => ['required'],
            'status' => ['required'],
        ];
    }
}
