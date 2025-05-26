<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => ['string'],
            'content' => ['string'],
            'image' => ['image', 'mimes:png,jpg,jpeg,gif'],
            'status' => [Rule::in(['draft','scheduled','published'])],
            'scheduled_time' => ["date_format:Y-m-d H:i:s", "after:now", "required_if:status,scheduled"],
            'platforms' => ['array', 'min:1'],
            'platforms.*' => ['required', 'numeric', 'exists:platforms,id']
        ];
    }
}
