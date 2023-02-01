<?php

namespace App\Http\Requests;

use App\Models\Caste;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCasteRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('caste_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:castes,id',
        ];
    }
}
