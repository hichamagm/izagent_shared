<?php

namespace Hichamagm\IzagentShared\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateServiceCriteriaExistence implements ValidationRule
{
    protected $resource;
    protected $name;
    protected $shouldExist;

    public function __construct(Closure $serviceCallback, string $name, $shouldExist = true)
    {
        $this->resource = $serviceCallback(); // The callback should return one of the models
        $this->name = $name;
        $this->shouldExist = $shouldExist;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!isset($this->resource->data)){
            $fail("Domain service is currently unavailable");
        }

        if($this->shouldExist == true && (isset($this->resource->data) && count($this->resource->data) == 0)){
            $fail("$this->name does not exist.");
        }elseif($this->shouldExist == false && (isset($this->resource->data) && count($this->resource->data) > 0)){
            $fail("$this->name already exist.");
        }
    }
}
