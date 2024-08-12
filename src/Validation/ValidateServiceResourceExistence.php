<?php

namespace Hichamagm\IzagentShared\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateServiceResourceExistence implements ValidationRule
{
    protected $resource;
    protected $name;

    public function __construct(Closure $serviceCallback, string $name)
    {
        $this->resource = $serviceCallback(); // The callback should return one of the models
        $this->name = $name;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!isset($this->resource->id)){
            $fail("$this->name does not exist.");
        }
    }
}
