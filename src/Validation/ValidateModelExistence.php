<?php

namespace Hichamagm\IzagentShared\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;

class ValidateModelExistence implements ValidationRule
{
    protected $model;
    protected $modelName;
    protected $shouldExist;

    public function __construct(Builder $model, $modelName, $shouldExist = true)
    {
        $this->model = $model;
        $this->modelName = $modelName;
        $this->shouldExist = $shouldExist;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = $this->model->exists();
        if($this->shouldExist && !$exists){
            $fail("$this->modelName not found");
        }

        if(!$this->shouldExist && $exists){
            $fail("$this->modelName already exists");
        }
    }
}
