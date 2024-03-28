<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "chart" => ["required", "array"],
            "chart.groupedColumn" => ["sometimes", "string"],
            "chart.type" => ["required", "string", Rule::in(['sum', 'count'])],
            "chart.sum" => ["present_if:chart.type,sum", "string"]
        ];
    }
}
