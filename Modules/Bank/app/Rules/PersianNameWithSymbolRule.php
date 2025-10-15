<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PersianNameWithSymbolRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[\/ آبپتثجچحخدذرزژسشصضطظعغفقکكگلمنوهیئيئءۀةؤءًٌٍَُِّأإآىءيا]+$/', $value)) {
            $fail(__('The :attribute should only contain Persian letters, eslash character and spaces.', [$attribute]));
        }
    }
}
