<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\AppointmentNature;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdvertisementRequest extends FormRequest
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
            'advertisement_no' => ['required', 'string', 'max:64', Rule::unique('advertisements', 'advertisement_no')->ignore($this->route('advertisement'))],
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'advertisement_type_id' => ['required', 'integer', 'exists:advertisement_types,id'],
            'appointment_nature' => ['required', Rule::enum(AppointmentNature::class)],
            'organisational_unit_id' => ['nullable', 'integer', 'exists:organisational_units,id'],
            'default_opening_date' => ['required', 'date'],
            'default_closing_date' => ['required', 'date', 'after:default_opening_date'],
            'default_payment_closing_date' => ['nullable', 'date', 'after_or_equal:default_opening_date'],
            'default_fee' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $nature = AppointmentNature::tryFrom((string) $this->input('appointment_nature'));

            // Local recruitment is administered at faculty level (DR-010), so
            // a local advertisement must name the unit that administers it.
            // Without one there is nobody the scoped roles resolve to.
            if ($nature?->requiresOrganisationalUnit() === true && $this->input('organisational_unit_id') === null) {
                $validator->errors()->add('organisational_unit_id', __('recruitment.unit_required_for_local'));
            }

            $payment = $this->input('default_payment_closing_date');
            $closing = $this->input('default_closing_date');

            // Payment may close with the application or before it, never
            // after: a candidate who can pay after the closing date has been
            // told the deadline is later than it is.
            if ($payment !== null && $closing !== null && strtotime((string) $payment) > strtotime((string) $closing)) {
                $validator->errors()->add('default_payment_closing_date', __('recruitment.payment_after_closing'));
            }
        });
    }
}
