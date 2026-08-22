<?php

namespace App\Http\Requests;

use App\Models\OtherDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOtherDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('other_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:other_details,id',
        ];
    }
}
