<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
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
            // Not `digits:6`: a recovery code satisfies the same field and is
            // not numeric. Length is checked against the configured code
            // length by the verifier, which knows which of the two it is.
            'code' => ['required', 'string', 'max:32'],
        ];
    }

    /**
     * The six boxes are one field with JavaScript and one field without it, so
     * the parts are joined before validation rather than after.
     */
    protected function prepareForValidation(): void
    {
        $code = $this->input('code');

        if (is_array($code)) {
            $this->merge(['code' => implode('', array_map('strval', $code))]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => __('auth.otp_required'),
        ];
    }
}
