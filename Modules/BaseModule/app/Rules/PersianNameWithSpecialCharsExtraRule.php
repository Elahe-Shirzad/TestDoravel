<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PersianNameWithSpecialCharsExtraRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            !preg_match(
                pattern: '/^[\p{Arabic}\p{N}\s()\/:%*\-!?؟.]+$/u',
                subject: $value
            )
        ) {
            $fail(
                __(
                    'The :attribute should only contain Persian/Arabic letters, numbers, dot(.), spaces, and characters () / : % * - ! ؟.',
                    [$attribute]
                )
            );
        }
    }
}
