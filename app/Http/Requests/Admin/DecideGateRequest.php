<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DecideGateRequest extends FormRequest
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
            'gate' => ['required', Rule::enum(EligibilityGate::class)],
            // Nullable, and the nullability is the point: clearing a decision
            // back to pending is a legitimate act, and pending is not
            // rejected.
            'decision' => ['nullable', Rule::enum(GateDecision::class)],
            'remark' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $decision = GateDecision::tryFrom((string) $this->input('decision'));

            // A rejection without a reason is not appealable, and an
            // unappealable rejection is one the University cannot defend.
            if ($decision === GateDecision::Rejected && trim((string) $this->input('remark')) === '') {
                $validator->errors()->add('remark', __('scrutiny.remark_required'));
            }
        });
    }
}
