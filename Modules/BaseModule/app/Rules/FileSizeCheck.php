<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class FileSizeCheck implements ValidationRule
{
    protected $maxSize;

    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // TODO: Implement file upload handling for large files
        $fileSize = ceil($value->getSize() / 1024);
        $maxSize = ceil($this->maxSize / 1024);
        if (is_object($value)) {
            if ($fileSize > $this->maxSize) {
                if ($this->maxSize <= 1024) {
                    $fail(__("The :attribute can't more than :maxSize KB.", [$attribute, "maxSize" => $this->maxSize]));
                } else {
                    $fail(__("The :attribute can't more than :maxSize MB.", [$attribute, "maxSize" => $maxSize]));
                }
            }
        } else {
            if ($fileSize > $this->maxSize) {
                $fail(__("The :attribute is very large.", [$attribute]));
            }
        }
    }
}
