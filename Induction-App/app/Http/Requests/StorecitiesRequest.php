<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorecitiesRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your auth needs
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'district_id' => [
                'required',
                'integer',
                Rule::exists('districts', 'id')
            ],
        ];
    }
}
