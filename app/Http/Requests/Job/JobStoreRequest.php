<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request for storing a new job.
 */
class JobStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return true; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
return [
        'title'             => 'required|string|max:255',
        'department_id'     => 'required|exists:departments,id',
        'designation_id'    => 'nullable|exists:designations,id',
        'description'       => 'required|string',
        'vacancy'           => 'required|integer|min:1',
        'deadline'          => 'required|date|after:today',
        'min_salary'        => 'nullable|numeric|min:0|required_with:max_salary',
        'max_salary'        => 'nullable|numeric|min:0|gte:min_salary',
        'salary_currency'   => 'nullable|string|in:ETB,USD,EUR',
        'salary_negotiable' => 'boolean',
        'show_salary'       => 'boolean',
    ];
    }
}