<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class CandidateStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'job_id'        => 'required|exists:jobs,id',
            'full_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:candidates,email',
            'phone'         => 'required|string|max:20',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter'  => 'nullable|string',
        ];
    }
}