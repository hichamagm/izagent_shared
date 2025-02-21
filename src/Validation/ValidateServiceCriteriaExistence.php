<?php

namespace Hichamagm\IzagentShared\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ValidateServiceCriteriaExistence implements ValidationRule
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
        $data = $this->resource->json();
        
        if(!$this->resource->successful()){
            $fail("$this->name service is currently unavailable");
        }

        if($this->shouldExist == true && (isset($data["data"]) && count($data["data"]) == 0)){
            $fail("$this->name does not exist.");
        }elseif($this->shouldExist == false && (isset($data["data"]) && count($data["data"]) > 0)){
            $fail("$this->name already exist.");
        }
    }
}
