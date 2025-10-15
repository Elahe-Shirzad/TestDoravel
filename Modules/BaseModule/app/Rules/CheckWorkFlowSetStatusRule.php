<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckWorkFlowSetStatusRule implements ValidationRule
{
    private $section;

    /**
     * @param $section
     */
    public function __construct($section)
    {
        $this->section = $section;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!settable($value, $this->section)) {
            $fail(__('basemodule::message.not_allow_to_set_status'));
        }
    }
}
