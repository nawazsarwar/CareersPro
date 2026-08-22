<?php

namespace App\Http\Requests;

use App\Models\Traed;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTraedRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('traed_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:traeds,id',
        ];
    }
}
