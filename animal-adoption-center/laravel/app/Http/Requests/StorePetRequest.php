<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Dog,Cat,Bird,Rabbit,Other'],
            'breed' => ['nullable', 'string', 'max:255'],
            'age' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:Male,Female,Unknown'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:Available,Pending,Adopted'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
        ];
    }
}
