<?php

namespace App\Http\Requests\Shift;

use Illuminate\Foundation\Http\FormRequest;

class ShiftStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                   => 'required|string|max:255|unique:shifts,name',
            'start_time'             => 'required',
            'end_time'               => 'required',
            'break_start_time'       => 'nullable',
            'break_end_time'         => 'nullable',
            'late_threshold_minutes' => 'required|integer|min:0',
            'half_day_minutes'       => 'nullable|integer|min:60',
            'overtime_rate'          => 'required|numeric|min:1',
            'is_default'             => 'sometimes|boolean',
        ];
    }
}