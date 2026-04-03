<?php

namespace App\Modules\Comments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming admin middleware handles authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'commentable_type' => [
                'required',
                'string',
                Rule::in(['App\Modules\Books\Models\Book', 'App\Modules\Articles\Models\Article', 'App\Modules\Pages\Models\Page', 'App\Modules\Products\Models\Product', 'App\Modules\News\Models\News'])
            ],
            'commentable_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id'
            ],
            'stars' => [
                'nullable',
                'integer',
                'between:1,5'
            ],
            'name' => [
                'nullable',
                'string',
                'max:255'
            ],
            'email' => [
                'nullable',
                'email',
                'max:255'
            ],
            'content' => [
                'required',
                'string',
                'max:5000'
            ],
            'status' => [
                'required',
                'string',
                Rule::in(['pending', 'approved', 'rejected', 'spam', 'hidden', 'deleted'])
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'commentable_type.required' => 'Please select a content type.',
            'commentable_type.in' => 'Invalid content type selected.',
            'commentable_id.required' => 'Content ID is required.',
            'commentable_id.integer' => 'Content ID must be a valid number.',
            'commentable_id.min' => 'Content ID must be greater than 0.',
            'user_id.exists' => 'Selected user does not exist.',
            'stars.between' => 'Rating must be between 1 and 5 stars.',
            'content.required' => 'Comment text is required.',
            'content.max' => 'Comment text cannot exceed 5000 characters.',
            'email.email' => 'Please enter a valid email address.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.'
        ];
    }
}
