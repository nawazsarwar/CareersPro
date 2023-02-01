<?php

namespace App\Http\Requests;

use App\Models\PostalCode;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPostalCodeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('postal_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:postal_codes,id',
        ];
    }
}
