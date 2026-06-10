<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoArabic implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) return;

        // يمنع أي حرف عربي
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $value)) {
            $fail('The :attribute must not contain Arabic characters.');
        }
    }
}