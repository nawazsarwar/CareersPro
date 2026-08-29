<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\Cadre;
use App\Enums\SelectionMethod;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDesignationRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', 'regex:/^[A-Z0-9\-]+$/', Rule::unique('designations', 'code')->ignore($this->route('designation'))],
            'name' => ['required', 'string', 'max:191'],
            'cadre' => ['required', Rule::enum(Cadre::class)],
            'group' => ['nullable', 'in:A,B,C'],
            'pay_level' => ['required', 'string', 'max:50'],
            'min_age' => ['nullable', 'integer', 'between:18,70'],
            'max_age' => ['nullable', 'integer', 'between:18,70'],
            'selection_method' => ['required', Rule::enum(SelectionMethod::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $cadre = Cadre::tryFrom((string) $this->input('cadre'));
            $group = $this->input('group');

            // A Group on a teaching cadre is a data error, not an empty field.
            if ($cadre?->hasGroup() === true && $group === null) {
                $validator->errors()->add('group', __('establishment.group_required'));
            }

            if ($cadre?->hasGroup() === false && $group !== null) {
                $validator->errors()->add('group', __('establishment.group_not_allowed'));
            }

            $min = $this->input('min_age');
            $max = $this->input('max_age');

            if ($min !== null && $max !== null && (int) $max < (int) $min) {
                $validator->errors()->add('max_age', __('establishment.age_order'));
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['code.regex' => __('establishment.code_format')];
    }
}
