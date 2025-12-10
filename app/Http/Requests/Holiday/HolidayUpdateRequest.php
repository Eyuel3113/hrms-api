<?php

namespace App\Http\Requests\Holiday;

use Illuminate\Foundation\Http\FormRequest;

class HolidayUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|required|string|max:255|unique:holidays,name',
            'date'           => 'sometimes|required|date',
            'ethiopian_date' => 'sometimes|required|string|max:255',
            'type'           => 'sometimes|required|in:national,religious,company',
            'is_recurring'   => 'sometimes|boolean',
            'description'    => 'nullable|string',
        ];
    }
}