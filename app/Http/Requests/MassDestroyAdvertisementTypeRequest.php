<?php

namespace App\Http\Requests;

use App\Models\AdvertisementType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAdvertisementTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('advertisement_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:advertisement_types,id',
        ];
    }
}
