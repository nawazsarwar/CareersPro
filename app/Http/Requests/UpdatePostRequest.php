<?php

namespace App\Http\Requests;

use App\Models\Post;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('post_edit');
    }

    public function rules()
    {
        return [
            'advertisement_id' => [
                'required',
                'integer',
            ],
            'posttype_id' => [
                'required',
                'integer',
            ],
            'serial_no' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'title' => [
                'string',
                'required',
            ],
            'subject' => [
                'string',
                'nullable',
            ],
            'slug' => [
                'string',
                'required',
                'unique:posts,slug,' . request()->route('post')->id,
            ],
            'vacancies' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'location' => [
                'string',
                'required',
            ],
            'pay_level' => [
                'string',
                'required',
            ],
            'pay_range' => [
                'string',
                'required',
            ],
            'fee' => [
                'required',
            ],
            'opening_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'closing_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'payment_closing_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'withdrawn' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'status' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'added_by_id' => [
                'required',
                'integer',
            ],
            'test_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'test_reporting_time' => [
                'string',
                'nullable',
            ],
            'gate_closing_time' => [
                'string',
                'nullable',
            ],
            'scheduled_test_start' => [
                'string',
                'nullable',
            ],
            'test_duration' => [
                'string',
                'nullable',
            ],
            'interview_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'interview_time' => [
                'string',
                'nullable',
            ],
            'interview_venue' => [
                'string',
                'nullable',
            ],
        ];
    }
}
