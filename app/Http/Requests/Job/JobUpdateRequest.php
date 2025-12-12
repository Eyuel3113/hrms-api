<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class JobUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'          => 'sometimes|required|string|max:255',
            'department_id'  => 'sometimes|required|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'description'    => 'sometimes|required|string',
            'vacancy'        => 'sometimes|required|integer|min:1',
            'deadline'       => 'sometimes|required|date',
            'status'         => 'sometimes|in:open,closed,on_hold',
        ];
    }
}