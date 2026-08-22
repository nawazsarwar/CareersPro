@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.applicationForm.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-forms.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.roll_no') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->roll_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.random_no') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->random_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.advertisement') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->advertisement->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.advertisement_title') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->advertisement_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.post') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->post->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.post_serial_no') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->post_serial_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.post_title') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->post_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.gender') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationForm::GENDER_SELECT[$applicationForm->gender] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.dob') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->dob }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.mobile') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->mobile }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.disability') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationForm::DISABILITY_SELECT[$applicationForm->disability] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.disability_type') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->disability_type->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.disability_percent') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->disability_percent }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.aadhaar_no') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->aadhaar_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.religion') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->religion->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.category') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->category->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.caste') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->caste->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.sub_caste') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->sub_caste }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.nationality') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->nationality->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.permanent_address') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->permanent_address }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.correspondence_address') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->correspondence_address }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.domicile_district') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->domicile_district }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.domicile_state') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->domicile_state->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.basic_details') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->basic_details }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.additional_details') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->additional_details }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.status') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.review') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->review }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.submitted') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->submitted }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.paid') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->paid }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.hardcopy_received') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationForm::HARDCOPY_RECEIVED_SELECT[$applicationForm->hardcopy_received] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.scrutinized') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationForm::SCRUTINIZED_SELECT[$applicationForm->scrutinized] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.scrutinized_by') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->scrutinized_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.scrutiny_remark') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->scrutiny_remark }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.eligible') }}
                                    </th>
                                    <td>
                                        {{ App\Models\ApplicationForm::ELIGIBLE_SELECT[$applicationForm->eligible] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.eligibility_remark') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->eligibility_remark }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.eligibility_updated_by') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->eligibility_updated_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.eligibility_updated_at') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->eligibility_updated_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.order_uid') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->order_uid }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.admitcard_downloaded_at') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->admitcard_downloaded_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.interview_letter_downloaded_at') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->interview_letter_downloaded_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.centre_name') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->centre_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.centre_code') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->centre_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.centre_address') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->centre_address }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.centre_city') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->centre_city }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.room_no') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->room_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.applicationForm.fields.seat_no') }}
                                    </th>
                                    <td>
                                        {{ $applicationForm->seat_no }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.application-forms.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection