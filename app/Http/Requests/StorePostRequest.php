<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string'],
            'content' => ['required','string'],
            'image' => ['image', 'mimes:png,jpg,jpeg,gif'],
            'status' => ['required', Rule::in(['draft','scheduled','published'])],
            'scheduled_time' => ["date_format:Y-m-d H:i:s", "after:now", "required_if:status,scheduled", "nullable"],
            'platforms' => ['required', 'array', 'min:1'],
            'platforms.*' => ['required', 'numeric', 'exists:platforms,id']
        ];
    }
}
