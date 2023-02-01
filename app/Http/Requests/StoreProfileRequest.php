<?php

namespace App\Http\Requests;

use App\Models\Profile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProfileRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('profile_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'first_name' => [
                'string',
                'required',
            ],
            'middle_name' => [
                'string',
                'nullable',
            ],
            'last_name' => [
                'string',
                'nullable',
            ],
            'spouse_name' => [
                'string',
                'nullable',
            ],
            'fathers_name' => [
                'string',
                'nullable',
            ],
            'mothers_name' => [
                'string',
                'nullable',
            ],
            'dob' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'mobile' => [
                'string',
                'nullable',
            ],
            'mobile_verified_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'alternate_mobile' => [
                'string',
                'nullable',
            ],
            'disability_percent' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'aadhaar_no' => [
                'string',
                'min:12',
                'max:12',
                'nullable',
            ],
            'sub_caste' => [
                'string',
                'nullable',
            ],
            'identity_marks' => [
                'string',
                'nullable',
            ],
            'verified' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'locked' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'conviction_reason' => [
                'string',
                'nullable',
            ],
            'debarred_reason' => [
                'string',
                'nullable',
            ],
            'vigilance_reason' => [
                'string',
                'nullable',
            ],
        ];
    }
}
