<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request for storing a new candidate application.
 */
class CandidateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return true; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_id'        => 'required|exists:job_postings,id',
            'full_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:candidates,email',
            'phone'         => 'required|string|max:20',
            'cv'            => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter'  => 'nullable|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $jobId = $this->input('job_id');
            if ($jobId) {
                // Use the simplified model lookup directly
                $job = \App\Models\Job::find($jobId);
                
                if ($job) {
                    if ($job->status !== 'open') {
                        $validator->errors()->add('job_id', 'This job is no longer accepting applications.');
                    }

                    // Check if deadline has passed. 
                    // effective deadline is the end of the deadline day.
                    if (\Carbon\Carbon::parse($job->deadline)->endOfDay()->isPast()) {
                         $validator->errors()->add('job_id', 'The deadline for this job has passed.');
                    }
                }
            }
        });
    }
}