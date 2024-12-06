<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $vaildation =  [
            'name' => 'required|string|max:255|unique:categories,name',
            'content' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ];

        if ($this->isMethod('put')) {
            $categoryId = $this->route('category'); 
            $validation['name'] = 'required|string|max:255|unique:categories,name,' . $categoryId;
        }

        return $vaildation;
    }
}
