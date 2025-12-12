<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Or add proper authorization logic
    }

    public function rules()
    {
        return [
            'induction_phase_id' => [
                'required',
                'exists:induction_phases,id'
            ],
            'post_category_id' => [
                'required',
                'exists:post_categories,id'
            ],
            'qualification_type_id' => [
                'required',
                'exists:qualification_types,id'
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($this->post)
            ],
            'number_post' => [
                'required',
                'integer',
                'min:1'
            ],
            'fee_post' => [
                'required',
                'numeric',
                'min:0'
            ],
            'min_age' => [
                'required',
                'integer',
                'min:18',
                'max:60'
            ],
            'max_age' => [
                'required',
                'integer',
                'min:18',
                'max:60',
                'gte:min_age'
            ],
            'post_abbreviation' => [
                'required',
                'string',
                'max:10',
                Rule::unique('posts')->ignore($this->post)
            ],
            'post_gender' => [
                'required',
                Rule::in(['Male', 'Female', 'Both'])
            ],
            'bps' => [
                'required',
                'integer',
                'min:1',
                'max:22'
            ],
            'requirements' => [
                'nullable',
                'string'
            ],
            'degree_required' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function messages()
    {
        return [
            'max_age.gte' => 'Maximum age must be greater than or equal to minimum age.',
            'bps.min' => 'BPS must be at least 1.',
            'bps.max' => 'BPS cannot be greater than 22.',
        ];
    }
}
