<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'dob' => ['nullable', 'date'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'pwd_status' => ['nullable', 'boolean'],
            'ex_serviceman' => ['nullable', 'boolean'],
        ];
    }
}
