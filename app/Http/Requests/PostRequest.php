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
            'title'         => 'required|max:100|unique:posts,title',
            'content'       => 'required',
            'category_id'   => 'required|exists:categories,id',
            'tags'          => 'array|nullable',
            'tags.*'        => 'exists:tags,id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($this->isMethod('put')) {
            $post_id = $this->route('post'); 
            $vaildation['title'] = 'required|string|max:100|unique:posts,title,' . $post_id;
        }

        return $vaildation;
    }
}
