<?php

namespace App\Http\Requests\Designation;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255|unique:designations,title',
            'description' => 'nullable|string',
            'status'    => 'nullable|string|in:active,inactive',
            'department_id' => 'required|exists:departments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Designation title is required.',
            'title.unique'   => 'This designation title already exists.',
        ];
    }
}