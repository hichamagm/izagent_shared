<?php

namespace Hichamagm\IzagentShared\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateServiceResourceExistence implements ValidationRule
{
    protected $resource;
    protected $name;
    protected $shouldExist;

    public function __construct($response, string $name, $shouldExist = true)
    {
        $this->resource = $response; // The callback should return one of the models
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
        if($this->shouldExist == true && !isset($this->resource->id)){
            $fail("$this->name does not exist.");
        }elseif($this->shouldExist == false && isset($this->resource->id)){
            $fail("$this->name already exist.");
        }
    }
}
