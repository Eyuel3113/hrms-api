<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class TrainingStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'trainer_name'      => 'nullable|string|max:255',
            'location'          => 'nullable|string|max:255',
            'incentive_amount'  => 'nullable|numeric|min:0|required_if:has_incentive,true',
            'has_incentive'     => 'boolean',
            'type'              => 'required|in:internal,external,certification',
            'is_mandatory'      => 'boolean',
        ];
    }
}