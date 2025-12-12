<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;

class StoreQuotaSettingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            // Validate that the request is an array
            '*' => 'required|array',

            // Validate each quota record in the array
            '*.induction_phase_id' => 'required|exists:induction_phases,id',
            '*.post_id' => 'required|exists:posts,id',
            '*.province_id' => 'nullable|exists:provinces,id',
            '*.merit' => 'required|integer|min:0',
            '*.women' => 'required|integer|min:0',
            '*.minority' => 'required|integer|min:0',
            '*.special_persons' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            '*.induction_phase_id.required' => 'The induction phase ID is required for all records.',
            '*.induction_phase_id.exists' => 'The selected induction phase is invalid.',
            '*.post_id.required' => 'The post ID is required for all records.',
            '*.post_id.exists' => 'The selected post is invalid.',
            '*.province_id.exists' => 'The selected province is invalid.',
            '*.merit.required' => 'Merit value is required.',
            '*.merit.integer' => 'Merit must be a valid number.',
            '*.merit.min' => 'Merit must be at least 0.',
            '*.women.required' => 'Women quota value is required.',
            '*.women.integer' => 'Women quota must be a valid number.',
            '*.women.min' => 'Women quota must be at least 0.',
            '*.minority.required' => 'Minority quota value is required.',
            '*.minority.integer' => 'Minority quota must be a valid number.',
            '*.minority.min' => 'Minority quota must be at least 0.',
            '*.special_persons.required' => 'Special persons quota value is required.',
            '*.special_persons.integer' => 'Special persons quota must be a valid number.',
            '*.special_persons.min' => 'Special persons quota must be at least 0.',
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation()
    {
        $this->validateTotalQuotas();
    }

    /**
     * Custom validation to ensure total quotas don't exceed available posts
     */
    private function validateTotalQuotas()
    {
        $validatedData = $this->validated();

        // Group by induction_phase_id and post_id to calculate totals
        $groupedData = collect($validatedData)->groupBy(function ($item) {
            return $item['induction_phase_id'] . '_' . $item['post_id'];
        });

        foreach ($groupedData as $groupKey => $group) {
            // Extract post_id from the first record to get post details
            $firstRecord = $group->first();
            $postId = $firstRecord['post_id'];

            // Get the total number of posts available
            $post = Post::find($postId);
            if (!$post) {
                continue;
            }

            $totalAvailablePosts = $post->number_post;

            // Calculate total quota allocation for this group
            $totalQuota = $group->sum(function ($record) {
                return $record['merit'] + $record['women'] + $record['minority'] + $record['special_persons'];
            });

            // Check if total quota exceeds available posts
            if ($totalQuota > $totalAvailablePosts) {
                $this->addCustomValidationErrors([
                    'quota_exceeded' => "Total quota allocation ({$totalQuota}) exceeds available posts ({$totalAvailablePosts}) for the selected post."
                ]);
            }
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator);
    }

    /**
     * Add custom validation errors
     */
    private function addCustomValidationErrors($errors)
    {
        $validator = \Validator::make([], []);
        foreach ($errors as $key => $message) {
            $validator->errors()->add($key, $message);
        }

        throw new \Illuminate\Validation\ValidationException($validator);
    }
}
