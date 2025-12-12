<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class JobStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'department_id'  => 'required|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'description'    => 'required|string',
            'vacancy'        => 'required|integer|min:1',
            'deadline'       => 'required|date|after:today',
        ];
    }
}