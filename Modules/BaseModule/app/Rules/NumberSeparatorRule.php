<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NumberSeparatorRule implements ValidationRule
{
    protected int|float $max;
    protected bool $isString;

    public function __construct(int|float $max, bool $isString = false)
    {
        $this->max = $max;
        $this->isString = $isString;
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) {
            $isInvalid = $this->isString
                ? strlen($value) > $this->max
                : $value > $this->max;

            if ($isInvalid) {
                $fail(__('basemodule::validation.max', [
                    'max' => numberFormatter($this->max),
                ]));
            }
        }
    }
}
