<?php

namespace App\Http\Requests;

use App\Models\PostType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPostTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('post_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:post_types,id',
        ];
    }
}
