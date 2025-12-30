<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Personal Info
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employee_personal_infos,email',
            'phone' => 'nullable|string|max:20|unique:employee_personal_infos,phone',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nationality' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',

            // Professional Info
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'joining_date' => 'required|date',
            'ending_date' => 'nullable|date|after:joining_date',
            'employment_type' => 'required|in:full-time,part-time,contract,freelance,intern',
            'basic_salary' => 'required|numeric|min:1',
            'salary_currency' => 'required|string|size:3',
            'bank_name' => 'nullable|string',
            'bank_account_number' => 'nullable|string|unique:employee_professional_infos,bank_account_number',
            'tax_id' => 'nullable|string|unique:employee_professional_infos,tax_id',
        ];
    }
}
