<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SlugRule implements ValidationRule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[-a-zA-Z0-9_]+$/', $value)) {
            $fail(__('The :attribute should only contain English letters, numbers, "-" and "_".', [$attribute]));
        }
    }
}
