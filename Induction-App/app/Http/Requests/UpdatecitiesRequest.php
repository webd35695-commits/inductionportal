<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatecitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities')->ignore($this->city->id)
            ],
            'district_id' => 'required|exists:districts,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The city name is required',
            'name.unique' => 'This city name already exists',
            'district_id.required' => 'Please select a district',
            'district_id.exists' => 'The selected district is invalid'
        ];
    }
}
