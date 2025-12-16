<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class TrainingUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'             => 'sometimes|required|string|max:255',
            'description'       => 'sometimes|required|string',
            'start_date'        => 'sometimes|required|date',
            'end_date'          => 'sometimes|required|date|after_or_equal:start_date',
            'trainer_name'      => 'nullable|string|max:255',
            'location'          => 'nullable|string|max:255',
            'incentive_amount'  => 'nullable|numeric|min:0|required_if:has_incentive,true',
            'has_incentive'     => 'sometimes|boolean',
            'type'              => 'sometimes|required|in:internal,external,certification',
            'is_mandatory'      => 'sometimes|boolean',
            'is_active'         => 'sometimes|boolean',
        ];
    }
}