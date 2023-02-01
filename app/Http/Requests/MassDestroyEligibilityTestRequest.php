<?php

namespace App\Http\Requests;

use App\Models\EligibilityTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEligibilityTestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('eligibility_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:eligibility_tests,id',
        ];
    }
}
