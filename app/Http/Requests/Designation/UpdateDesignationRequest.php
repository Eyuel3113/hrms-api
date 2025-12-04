<?php

namespace App\Http\Requests\Designation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $designationId = $this->route('designation'); 

        return [
            'title'       => 'nullable|string|max:255|unique:designations,title,' . $designationId,
            'description' => 'nullable|string',
            'department_id' => 'sometimes|exists:departments,id',
        
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'This designation title is already taken by another designation.',
        ];
    }
}