<?php

namespace App\Http\Requests\Holiday;

use Illuminate\Foundation\Http\FormRequest;

class HolidayStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255|unique:holidays,name',
            'date'           => 'required|date',
            'ethiopian_date' => 'required|string|max:255',
            'type'           => 'required|in:national,religious,company',
            'is_recurring'   => 'sometimes|boolean',
            'description'    => 'nullable|string',
        ];
    }
}