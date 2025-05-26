<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PostsFiltrationRequest extends FormRequest
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
            'paginate' => ['boolean'],
            'status' => ['string', Rule::in(['draft', 'published', 'scheduled'])],
            'start_date' => ['date_format:Y-m-d'],
            'end_date' => ['date_format:Y-m-d', 'after:start_date']
        ];
    }
}
