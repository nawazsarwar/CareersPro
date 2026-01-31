<?php

namespace App\Http\Requests;

use App\Models\InstitutionsAttended;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInstitutionsAttendedRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('institutions_attended_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'name_of_school' => [
                'string',
                'required',
            ],
            'name_of_college' => [
                'string',
                'required',
            ],
            'university_board_id' => [
                'required',
                'integer',
            ],
            'year_of_joining' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'year_of_leaving' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'unique:institutions_attendeds,year_of_leaving,' . request()->route('institutions_attended')->id,
            ],
        ];
    }
}
