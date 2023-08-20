<?php

namespace App\Http\Requests;

use App\Rules\ValidImageUrl;
use Illuminate\Foundation\Http\FormRequest;

class DescribeImageRequest extends FormRequest
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'url' => ['bail', 'required', 'url:http,https', app(ValidImageUrl::class)]
        ];
    }
}
