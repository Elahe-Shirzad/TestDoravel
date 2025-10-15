<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphabetRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[a-zA-Z آبپتثجچحخدذرزژسشصضطظعغفقکكگلمنوهیئيئءۀةؤءًٌٍَُِّأإآىءيا]+$/', $value)) {
            $fail(__('The :attribute should only contain Persian letters , English letters and spaces.', [$attribute]));
        }
    }
}
