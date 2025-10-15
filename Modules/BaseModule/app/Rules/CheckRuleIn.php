<?php

namespace Modules\BaseModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CheckRuleIn implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    protected $rule;
    protected $message;


    public function __construct($rule, $message = null)
    {
        $this->rule = $rule;
        $this->message = $message;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Validator::make([
            "value" => $value,
        ], [
            "value" => [
                Rule::in($this->rule)
            ],
        ]);

        if ($validator->fails()) {
            if ($this->message) {
                $fail($this->message);
            }
            $fail(__('The :attribute is not correct.', [$attribute]));
        }
    }
}
