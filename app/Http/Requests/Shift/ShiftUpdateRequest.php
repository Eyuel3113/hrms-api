<?php

namespace App\Http\Requests\Shift;

use Illuminate\Foundation\Http\FormRequest;

class ShiftUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name'                   => "sometimes|required|string|max:255|unique:shifts,name,{$id}",
            'start_time'             => 'sometimes|required',
            'end_time'               => 'sometimes|required',
            'break_start_time'       => 'sometimes|nullable',
            'break_end_time'         => 'sometimes|nullable',
            'late_threshold_minutes' => 'sometimes|required|integer|min:0',
            'half_day_minutes'       => 'sometimes|required|integer|min:60',
            'overtime_rate'          => 'sometimes|required|numeric|min:1',
            'is_default'             => 'sometimes|boolean',
        ];
    }
}