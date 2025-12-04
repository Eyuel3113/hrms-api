<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => "nullable|string|unique:departments,name,$id,id",
            'code' => "nullable|string|unique:departments,code,$id,id",
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
