<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MobileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // No uniqueness rule (DR-023): a shared family handset is
            // legitimate. Accounts sharing a mobile are surfaced in the M23
            // data-quality report, not blocked here.
            'mobile' => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'mobile.regex' => __('auth.mobile_invalid'),
        ];
    }
}
