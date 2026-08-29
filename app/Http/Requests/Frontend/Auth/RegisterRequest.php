<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:191', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                // M03 §5. The previous build used Password::defaults(), which
                // is eight characters and nothing else.
                Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            'consent' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => __('auth.email_taken'),
            'password.min' => __('auth.password_policy'),
            'consent.accepted' => __('auth.consent_required'),
        ];
    }
}
