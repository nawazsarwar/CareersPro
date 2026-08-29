<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\RoleSlug;
use App\Models\OrganisationalUnit;
use App\Models\Role;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;    // the route's policy decides; this decides shape only
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'organisational_unit_id' => ['nullable', 'integer', 'exists:organisational_units,id'],
        ];
    }

    /**
     * M25-R12 — an OU-scoped role assigned without a unit, or a
     * university-wide role assigned with one, are both refused.
     *
     * The first matters most: a dean_office_* assignment missing its unit
     * would otherwise read as university-wide, which is the widest possible
     * failure from the narrowest possible mistake.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $role = Role::query()->find($this->integer('role_id'));

            if ($role === null) {
                return;
            }

            $slug = $role->enum();
            $unitId = $this->input('organisational_unit_id');

            if ($slug?->requiresOrganisationalUnit() === true && $unitId === null) {
                $validator->errors()->add('organisational_unit_id', __('access.unit_required'));

                return;
            }

            if ($slug?->requiresOrganisationalUnit() === false && $unitId !== null && $slug !== RoleSlug::ScrutinyOfficer) {
                $validator->errors()->add('organisational_unit_id', __('access.unit_not_allowed'));

                return;
            }

            if ($unitId !== null) {
                $unit = OrganisationalUnit::query()->find($unitId);

                // A draft unit is not yet a place anybody works.
                if ($unit !== null && ! $unit->isPublished()) {
                    $validator->errors()->add('organisational_unit_id', __('access.unit_unpublished'));
                }
            }
        });
    }
}
