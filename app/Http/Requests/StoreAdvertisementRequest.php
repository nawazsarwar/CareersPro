<?php

namespace App\Http\Requests;

use App\Models\Advertisement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAdvertisementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advertisement_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'slug' => [
                'string',
                'nullable',
            ],
            'dated' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'type_id' => [
                'required',
                'integer',
            ],
            'advertisement_url' => [
                'string',
                'nullable',
            ],
            'default_open_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'default_end_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'default_payment_end_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'approved_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'status' => [
                'string',
                'required',
            ],
        ];
    }
}
