<?php

namespace App\Http\Requests;

use App\Models\OtherDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOtherDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('other_detail_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'fellowship_undergraduate' => [
                'string',
                'max:180',
                'required',
            ],
            'fellowship_graduate' => [
                'string',
                'max:180',
                'nullable',
            ],
            'fellowship_postgraduate' => [
                'string',
                'max:180',
                'nullable',
            ],
            'phd_thesis_title' => [
                'string',
                'max:180',
                'nullable',
            ],
            'research_phd_awarded' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_phd_thesis' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_phd_total_scholars' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_mphil_awarded' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_mphil_thesis' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_mphil_total_scholars' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_other_awarded' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_other_thesis' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'research_other_total_scholars' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'current_pay_level' => [
                'string',
                'max:180',
                'nullable',
            ],
            'current_pay_range' => [
                'string',
                'max:180',
                'nullable',
            ],
            'current_basic_pay' => [
                'string',
                'max:180',
                'nullable',
            ],
            'current_pay_band' => [
                'string',
                'max:180',
                'nullable',
            ],
            'current_grade_pay' => [
                'string',
                'max:180',
                'nullable',
            ],
            'current_basic_pay_old' => [
                'string',
                'max:180',
                'nullable',
            ],
            'current_allowances' => [
                'string',
                'max:180',
                'nullable',
            ],
            'current_allowances_total' => [
                'string',
                'max:180',
                'nullable',
            ],
            'increment_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'joining_time' => [
                'string',
                'max:180',
                'nullable',
            ],
            'books_published' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'books_accepted' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'papers_published' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'papers_accepted' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'articles_published' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'articles_accepted' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'papers_read_published' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'papers_read_accepted' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'eca_university_administration' => [
                'string',
                'max:180',
                'nullable',
            ],
            'eca_student' => [
                'string',
                'max:180',
                'nullable',
            ],
            'eca_residential_student' => [
                'string',
                'max:180',
                'nullable',
            ],
            'eca_cultural' => [
                'string',
                'max:180',
                'nullable',
            ],
            'previous_applications' => [
                'string',
                'max:180',
                'nullable',
            ],
            'testimonial_1' => [
                'string',
                'nullable',
            ],
            'testimonial_2' => [
                'string',
                'max:180',
                'nullable',
            ],
            'testimonial_3' => [
                'string',
                'max:180',
                'nullable',
            ],
        ];
    }
}
