<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'mobile' => 'required|string|max:15',
            'gender' => 'required|string|in:Male,Female',
            'dateOfBirth' => 'required|date',
            'photo' => 'nullable|image|max:2048',
            'city' => 'required|int',
            'postalAddress' => 'required|string|max:255',
            'permanentAddress' => 'required|string|max:255',
            'religion' => 'required|string|max:50',
            'disabilityStatus' => 'required|string|in:Yes,No',
            'maritalStatus' => 'required|string|in:Single,Married,Divorced',
            'fatherName' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'mobile.required' => 'Mobile number is required',
            'gender.required' => 'Gender is required',
            'dateOfBirth.required' => 'Date of birth is required',
            'idNumber.required' => 'CNIC number is required',
            // Add more custom messages as needed
        ];
    }
}
