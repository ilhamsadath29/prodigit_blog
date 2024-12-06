<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
        $vaildation =  [
            'name' => 'required|string|max:255|unique:tags,name',
        ];

        if ($this->isMethod('put')) {
            $tagId = $this->route('tag'); 
            $validation['name'] = 'required|string|max:255|unique:tags,name,' . $tagId;
        }

        return $vaildation;
    }
}
