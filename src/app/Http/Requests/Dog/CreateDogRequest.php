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
        return true;
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
            'gender' => ['required', 'in:male,female'],
            'image' => ['required', 'image'],
            'description' => ['required'],
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'type' => ['required'],
            'status' => ['required'],
            'slider_images' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            // Общие сообщения
            'required' => 'Поле :attribute обязательно для заполнения.',
            'date_format' => 'Поле :attribute должно быть в формате ГГГГ-ММ-ДД.',
            'exists' => 'Выбранное значение для :attribute некорректно.',
            'image' => 'Файл в поле :attribute должен быть изображением.',

            // Кастомные сообщения для конкретных полей
            'parent_id.exists' => 'Выбранная родительская собака не существует.',
            'name.required' => 'Пожалуйста, укажите кличку собаки.',
            'gender.required' => 'Укажите пол собаки',
            'gender.in' => 'Выберите корректный пол из предложенных вариантов',
            'description.required' => 'Описание собаки обязательно для заполнения.',
            'birthdate.required' => 'Дата рождения обязательна для заполнения.',
            'birthdate.date_format' => 'Введите дату рождения в формате ГГГГ-ММ-ДД (например: 2023-05-15).',
            'type.required' => 'Необходимо указать тип (собака/щенок).',
            'status.required' => 'Необходимо указать статус собаки.',
        ];
    }

    public function attributes(): array
    {
        return [
            'parent_id' => 'родительская собака',
            'name' => 'кличка',
            'gender' => 'пол',
            'image' => 'изображение',
            'description' => 'описание',
            'birthdate' => 'дата рождения',
            'type' => 'тип',
            'status' => 'статус',
            'slider_images' => 'дополнительные фото',
        ];
    }
}
