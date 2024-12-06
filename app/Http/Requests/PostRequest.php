<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title'         => 'required|max:100|unique:posts,name',
            'content'       => 'required',
            'category_id'   => 'required|exists:categories,id',
            'tags'          => 'array|exists:tags,id',
            'img'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($this->isMethod('put')) {
            $postId = $this->route('post'); 
            $validation['name'] = 'required|string|max:100|unique:posts,name,' . $postId;
        }

        return $vaildation;
    }
}
