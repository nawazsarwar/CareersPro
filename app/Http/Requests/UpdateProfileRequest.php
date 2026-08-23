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
            'email' => ['required', 'email', 'unique:users,email,' . auth()->id()],
            'category_id' => ['nullable', 'exists:categories,id'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'dob' => ['nullable', 'date'],
            'pwbd_status' => ['nullable', 'boolean'],
            'ex_serviceman' => ['nullable', 'boolean'],
        ];
    }
}
