<?php

namespace App\Http\Requests;

use App\Models\ApplicationForm;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationFormRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_form_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'roll_no' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'random_no' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'advertisement_id' => [
                'required',
                'integer',
            ],
            'advertisement_title' => [
                'string',
                'nullable',
            ],
            'post_id' => [
                'required',
                'integer',
            ],
            'post_serial_no' => [
                'string',
                'nullable',
            ],
            'post_title' => [
                'string',
                'nullable',
            ],
            'name' => [
                'string',
                'nullable',
            ],
            'dob' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'mobile' => [
                'string',
                'nullable',
            ],
            'disability_percent' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'aadhaar_no' => [
                'string',
                'nullable',
            ],
            'sub_caste' => [
                'string',
                'nullable',
            ],
            'permanent_address' => [
                'string',
                'nullable',
            ],
            'correspondence_address' => [
                'string',
                'nullable',
            ],
            'domicile_district' => [
                'string',
                'nullable',
            ],
            'status' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'review' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'submitted' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'paid' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'eligibility_updated_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'order_uid' => [
                'string',
                'nullable',
            ],
            'admitcard_downloaded_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'interview_letter_downloaded_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'centre_name' => [
                'string',
                'nullable',
            ],
            'centre_code' => [
                'string',
                'nullable',
            ],
            'centre_address' => [
                'string',
                'nullable',
            ],
            'centre_city' => [
                'string',
                'nullable',
            ],
            'room_no' => [
                'string',
                'nullable',
            ],
            'seat_no' => [
                'string',
                'nullable',
            ],
        ];
    }
}
