<?php

namespace App\Http\Requests;

use App\Rules\MaxPosts;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $postId = $this->route('post');

        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
                $postId
                    ? Rule::unique('posts')->ignore($postId)
                    : 'unique:posts'
            ],
            'description' => 'required|string|min:10',
            'image' => 'nullable|file|mimes:jpg,png|max:2048',
            'user_id' => ['required', 'exists:users,id', new MaxPosts()],
            'tags' => 'nullable|string|max:255',
        ];
    }
}
