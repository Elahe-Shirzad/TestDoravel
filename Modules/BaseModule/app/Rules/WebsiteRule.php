<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class WebsiteRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // حذف فاصله‌های اضافی
        $value = trim($value);

        // اگر مقدار خالی است، نیازی به خطا نیست (nullable)
        if (empty($value)) {
            return;
        }

        // اگر URL معتبر نیست
        if (!filter_var($value, FILTER_VALIDATE_URL) &&
            !preg_match('/^(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(?:\/.*)?$/', $value)
        ) {
            $fail(__('The :attribute must be a valid website URL.', [$attribute]));
        }
    }
}
