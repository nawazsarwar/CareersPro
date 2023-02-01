@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.profile.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.profiles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.id') }}
                        </th>
                        <td>
                            {{ $profile->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.user') }}
                        </th>
                        <td>
                            {{ $profile->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.first_name') }}
                        </th>
                        <td>
                            {{ $profile->first_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.middle_name') }}
                        </th>
                        <td>
                            {{ $profile->middle_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.last_name') }}
                        </th>
                        <td>
                            {{ $profile->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.spouse_name') }}
                        </th>
                        <td>
                            {{ $profile->spouse_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.marital_status') }}
                        </th>
                        <td>
                            {{ $profile->marital_status->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.fathers_name') }}
                        </th>
                        <td>
                            {{ $profile->fathers_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.mothers_name') }}
                        </th>
                        <td>
                            {{ $profile->mothers_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.dob') }}
                        </th>
                        <td>
                            {{ $profile->dob }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.gender') }}
                        </th>
                        <td>
                            {{ App\Models\Profile::GENDER_SELECT[$profile->gender] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.mobile') }}
                        </th>
                        <td>
                            {{ $profile->mobile }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.mobile_verified_at') }}
                        </th>
                        <td>
                            {{ $profile->mobile_verified_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.alternate_mobile') }}
                        </th>
                        <td>
                            {{ $profile->alternate_mobile }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.pwd') }}
                        </th>
                        <td>
                            {{ App\Models\Profile::PWD_SELECT[$profile->pwd] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.disability_type') }}
                        </th>
                        <td>
                            {{ $profile->disability_type->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.disability_percent') }}
                        </th>
                        <td>
                            {{ $profile->disability_percent }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.aadhaar_no') }}
                        </th>
                        <td>
                            {{ $profile->aadhaar_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.religion') }}
                        </th>
                        <td>
                            {{ $profile->religion->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.category') }}
                        </th>
                        <td>
                            {{ $profile->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.caste') }}
                        </th>
                        <td>
                            {{ $profile->caste->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.sub_caste') }}
                        </th>
                        <td>
                            {{ $profile->sub_caste }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.nationality') }}
                        </th>
                        <td>
                            {{ $profile->nationality->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.place_of_birth') }}
                        </th>
                        <td>
                            {{ App\Models\Profile::PLACE_OF_BIRTH_SELECT[$profile->place_of_birth] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.district_of_birth') }}
                        </th>
                        <td>
                            {{ $profile->district_of_birth->district ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.state_of_birth') }}
                        </th>
                        <td>
                            {{ $profile->state_of_birth->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.domicile_state') }}
                        </th>
                        <td>
                            {{ $profile->domicile_state->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.domicile_district') }}
                        </th>
                        <td>
                            {{ $profile->domicile_district->district ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.identity_marks') }}
                        </th>
                        <td>
                            {{ $profile->identity_marks }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.remarks') }}
                        </th>
                        <td>
                            {{ $profile->remarks }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.verified') }}
                        </th>
                        <td>
                            {{ $profile->verified }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.locked') }}
                        </th>
                        <td>
                            {{ $profile->locked }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.conviction') }}
                        </th>
                        <td>
                            {{ App\Models\Profile::CONVICTION_RADIO[$profile->conviction] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.conviction_reason') }}
                        </th>
                        <td>
                            {{ $profile->conviction_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.debarred') }}
                        </th>
                        <td>
                            {{ App\Models\Profile::DEBARRED_RADIO[$profile->debarred] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.debarred_reason') }}
                        </th>
                        <td>
                            {{ $profile->debarred_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.vigilance') }}
                        </th>
                        <td>
                            {{ App\Models\Profile::VIGILANCE_RADIO[$profile->vigilance] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.profile.fields.vigilance_reason') }}
                        </th>
                        <td>
                            {{ $profile->vigilance_reason }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.profiles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection