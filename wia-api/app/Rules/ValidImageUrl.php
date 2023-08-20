<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidImageUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $content_type = get_headers($value, 1)['Content-Type'];

        if(!str_starts_with($content_type, 'image/')){
            $fail('Please provide a valid image URL');
        }
    }
}
