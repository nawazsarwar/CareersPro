<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateEstablishmentRequest extends FormRequest
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
            'sanctioned_count' => ['required', 'integer', 'min:0'],
            'sanction_order_ref' => ['required', 'string', 'max:191'],
            'sanctioned_on' => ['nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $filled = (int) DB::table('organisational_unit_designation')
                ->where('organisational_unit_id', $this->route('organisationalUnit')?->getKey())
                ->where('designation_id', $this->route('designation')?->getKey())
                ->value('filled_count');

            $requested = $this->integer('sanctioned_count');

            // Reducing below the filled count would leave serving staff in
            // posts the University no longer sanctions.
            if ($requested < $filled) {
                $validator->errors()->add('sanctioned_count', __('establishment.below_filled', [
                    'requested' => $requested,
                    'filled' => $filled,
                ]));
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['sanction_order_ref.required' => __('establishment.order_ref_required')];
    }
}
