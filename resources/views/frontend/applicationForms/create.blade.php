@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.applicationForm.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.application-forms.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.applicationForm.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="roll_no">{{ trans('cruds.applicationForm.fields.roll_no') }}</label>
                            <input class="form-control" type="number" name="roll_no" id="roll_no" value="{{ old('roll_no', '') }}" step="1">
                            @if($errors->has('roll_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('roll_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.roll_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="random_no">{{ trans('cruds.applicationForm.fields.random_no') }}</label>
                            <input class="form-control" type="number" name="random_no" id="random_no" value="{{ old('random_no', '') }}" step="1">
                            @if($errors->has('random_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('random_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.random_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="advertisement_id">{{ trans('cruds.applicationForm.fields.advertisement') }}</label>
                            <select class="form-control select2" name="advertisement_id" id="advertisement_id" required>
                                @foreach($advertisements as $id => $entry)
                                    <option value="{{ $id }}" {{ old('advertisement_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('advertisement'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('advertisement') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.advertisement_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="advertisement_title">{{ trans('cruds.applicationForm.fields.advertisement_title') }}</label>
                            <input class="form-control" type="text" name="advertisement_title" id="advertisement_title" value="{{ old('advertisement_title', '') }}">
                            @if($errors->has('advertisement_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('advertisement_title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.advertisement_title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="post_id">{{ trans('cruds.applicationForm.fields.post') }}</label>
                            <select class="form-control select2" name="post_id" id="post_id" required>
                                @foreach($posts as $id => $entry)
                                    <option value="{{ $id }}" {{ old('post_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('post'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('post') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.post_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="post_serial_no">{{ trans('cruds.applicationForm.fields.post_serial_no') }}</label>
                            <input class="form-control" type="text" name="post_serial_no" id="post_serial_no" value="{{ old('post_serial_no', '') }}">
                            @if($errors->has('post_serial_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('post_serial_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.post_serial_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="post_title">{{ trans('cruds.applicationForm.fields.post_title') }}</label>
                            <input class="form-control" type="text" name="post_title" id="post_title" value="{{ old('post_title', '') }}">
                            @if($errors->has('post_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('post_title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.post_title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.applicationForm.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ trans('cruds.applicationForm.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationForm.fields.gender') }}</label>
                            <select class="form-control" name="gender" id="gender">
                                <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationForm::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('gender', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gender') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.gender_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="dob">{{ trans('cruds.applicationForm.fields.dob') }}</label>
                            <input class="form-control date" type="text" name="dob" id="dob" value="{{ old('dob') }}">
                            @if($errors->has('dob'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dob') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.dob_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mobile">{{ trans('cruds.applicationForm.fields.mobile') }}</label>
                            <input class="form-control" type="text" name="mobile" id="mobile" value="{{ old('mobile', '') }}">
                            @if($errors->has('mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.mobile_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationForm.fields.disability') }}</label>
                            <select class="form-control" name="disability" id="disability">
                                <option value disabled {{ old('disability', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationForm::DISABILITY_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('disability', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('disability'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('disability') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.disability_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="disability_type_id">{{ trans('cruds.applicationForm.fields.disability_type') }}</label>
                            <select class="form-control select2" name="disability_type_id" id="disability_type_id">
                                @foreach($disability_types as $id => $entry)
                                    <option value="{{ $id }}" {{ old('disability_type_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('disability_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('disability_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.disability_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="disability_percent">{{ trans('cruds.applicationForm.fields.disability_percent') }}</label>
                            <input class="form-control" type="number" name="disability_percent" id="disability_percent" value="{{ old('disability_percent', '') }}" step="1">
                            @if($errors->has('disability_percent'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('disability_percent') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.disability_percent_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="aadhaar_no">{{ trans('cruds.applicationForm.fields.aadhaar_no') }}</label>
                            <input class="form-control" type="text" name="aadhaar_no" id="aadhaar_no" value="{{ old('aadhaar_no', '') }}">
                            @if($errors->has('aadhaar_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('aadhaar_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.aadhaar_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="religion_id">{{ trans('cruds.applicationForm.fields.religion') }}</label>
                            <select class="form-control select2" name="religion_id" id="religion_id">
                                @foreach($religions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('religion_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('religion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('religion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.religion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="category_id">{{ trans('cruds.applicationForm.fields.category') }}</label>
                            <select class="form-control select2" name="category_id" id="category_id">
                                @foreach($categories as $id => $entry)
                                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('category') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.category_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="caste_id">{{ trans('cruds.applicationForm.fields.caste') }}</label>
                            <select class="form-control select2" name="caste_id" id="caste_id">
                                @foreach($castes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('caste_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('caste'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('caste') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.caste_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sub_caste">{{ trans('cruds.applicationForm.fields.sub_caste') }}</label>
                            <input class="form-control" type="text" name="sub_caste" id="sub_caste" value="{{ old('sub_caste', '') }}">
                            @if($errors->has('sub_caste'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sub_caste') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.sub_caste_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nationality_id">{{ trans('cruds.applicationForm.fields.nationality') }}</label>
                            <select class="form-control select2" name="nationality_id" id="nationality_id">
                                @foreach($nationalities as $id => $entry)
                                    <option value="{{ $id }}" {{ old('nationality_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('nationality'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nationality') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.nationality_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="permanent_address">{{ trans('cruds.applicationForm.fields.permanent_address') }}</label>
                            <input class="form-control" type="text" name="permanent_address" id="permanent_address" value="{{ old('permanent_address', '') }}">
                            @if($errors->has('permanent_address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('permanent_address') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.permanent_address_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="correspondence_address">{{ trans('cruds.applicationForm.fields.correspondence_address') }}</label>
                            <input class="form-control" type="text" name="correspondence_address" id="correspondence_address" value="{{ old('correspondence_address', '') }}">
                            @if($errors->has('correspondence_address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('correspondence_address') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.correspondence_address_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="domicile_district">{{ trans('cruds.applicationForm.fields.domicile_district') }}</label>
                            <input class="form-control" type="text" name="domicile_district" id="domicile_district" value="{{ old('domicile_district', '') }}">
                            @if($errors->has('domicile_district'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('domicile_district') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.domicile_district_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="domicile_state_id">{{ trans('cruds.applicationForm.fields.domicile_state') }}</label>
                            <select class="form-control select2" name="domicile_state_id" id="domicile_state_id">
                                @foreach($domicile_states as $id => $entry)
                                    <option value="{{ $id }}" {{ old('domicile_state_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domicile_state'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('domicile_state') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.domicile_state_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="basic_details">{{ trans('cruds.applicationForm.fields.basic_details') }}</label>
                            <textarea class="form-control" name="basic_details" id="basic_details">{{ old('basic_details') }}</textarea>
                            @if($errors->has('basic_details'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('basic_details') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.basic_details_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="additional_details">{{ trans('cruds.applicationForm.fields.additional_details') }}</label>
                            <textarea class="form-control" name="additional_details" id="additional_details">{{ old('additional_details') }}</textarea>
                            @if($errors->has('additional_details'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('additional_details') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.additional_details_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="status">{{ trans('cruds.applicationForm.fields.status') }}</label>
                            <input class="form-control" type="number" name="status" id="status" value="{{ old('status', '') }}" step="1">
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ trans('cruds.applicationForm.fields.remarks') }}</label>
                            <textarea class="form-control" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                            @if($errors->has('remarks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remarks') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.remarks_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="review">{{ trans('cruds.applicationForm.fields.review') }}</label>
                            <input class="form-control" type="number" name="review" id="review" value="{{ old('review', '1') }}" step="1">
                            @if($errors->has('review'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('review') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.review_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="submitted">{{ trans('cruds.applicationForm.fields.submitted') }}</label>
                            <input class="form-control" type="number" name="submitted" id="submitted" value="{{ old('submitted', '0') }}" step="1">
                            @if($errors->has('submitted'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('submitted') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.submitted_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="paid">{{ trans('cruds.applicationForm.fields.paid') }}</label>
                            <input class="form-control" type="number" name="paid" id="paid" value="{{ old('paid', '') }}" step="1">
                            @if($errors->has('paid'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('paid') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.paid_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationForm.fields.hardcopy_received') }}</label>
                            <select class="form-control" name="hardcopy_received" id="hardcopy_received">
                                <option value disabled {{ old('hardcopy_received', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationForm::HARDCOPY_RECEIVED_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('hardcopy_received', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('hardcopy_received'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('hardcopy_received') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.hardcopy_received_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationForm.fields.scrutinized') }}</label>
                            <select class="form-control" name="scrutinized" id="scrutinized">
                                <option value disabled {{ old('scrutinized', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationForm::SCRUTINIZED_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('scrutinized', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('scrutinized'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('scrutinized') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.scrutinized_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="scrutinized_by_id">{{ trans('cruds.applicationForm.fields.scrutinized_by') }}</label>
                            <select class="form-control select2" name="scrutinized_by_id" id="scrutinized_by_id">
                                @foreach($scrutinized_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ old('scrutinized_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('scrutinized_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('scrutinized_by') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.scrutinized_by_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="scrutiny_remark">{{ trans('cruds.applicationForm.fields.scrutiny_remark') }}</label>
                            <textarea class="form-control" name="scrutiny_remark" id="scrutiny_remark">{{ old('scrutiny_remark') }}</textarea>
                            @if($errors->has('scrutiny_remark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('scrutiny_remark') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.scrutiny_remark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.applicationForm.fields.eligible') }}</label>
                            <select class="form-control" name="eligible" id="eligible">
                                <option value disabled {{ old('eligible', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\ApplicationForm::ELIGIBLE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('eligible', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('eligible'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eligible') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.eligible_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eligibility_remark">{{ trans('cruds.applicationForm.fields.eligibility_remark') }}</label>
                            <textarea class="form-control" name="eligibility_remark" id="eligibility_remark">{{ old('eligibility_remark') }}</textarea>
                            @if($errors->has('eligibility_remark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eligibility_remark') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.eligibility_remark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eligibility_updated_by_id">{{ trans('cruds.applicationForm.fields.eligibility_updated_by') }}</label>
                            <select class="form-control select2" name="eligibility_updated_by_id" id="eligibility_updated_by_id">
                                @foreach($eligibility_updated_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ old('eligibility_updated_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('eligibility_updated_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eligibility_updated_by') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.eligibility_updated_by_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eligibility_updated_at">{{ trans('cruds.applicationForm.fields.eligibility_updated_at') }}</label>
                            <input class="form-control datetime" type="text" name="eligibility_updated_at" id="eligibility_updated_at" value="{{ old('eligibility_updated_at') }}">
                            @if($errors->has('eligibility_updated_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eligibility_updated_at') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.eligibility_updated_at_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="order_uid">{{ trans('cruds.applicationForm.fields.order_uid') }}</label>
                            <input class="form-control" type="text" name="order_uid" id="order_uid" value="{{ old('order_uid', '') }}">
                            @if($errors->has('order_uid'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order_uid') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.order_uid_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="admitcard_downloaded_at">{{ trans('cruds.applicationForm.fields.admitcard_downloaded_at') }}</label>
                            <input class="form-control datetime" type="text" name="admitcard_downloaded_at" id="admitcard_downloaded_at" value="{{ old('admitcard_downloaded_at') }}">
                            @if($errors->has('admitcard_downloaded_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('admitcard_downloaded_at') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.admitcard_downloaded_at_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="interview_letter_downloaded_at">{{ trans('cruds.applicationForm.fields.interview_letter_downloaded_at') }}</label>
                            <input class="form-control datetime" type="text" name="interview_letter_downloaded_at" id="interview_letter_downloaded_at" value="{{ old('interview_letter_downloaded_at') }}">
                            @if($errors->has('interview_letter_downloaded_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('interview_letter_downloaded_at') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.interview_letter_downloaded_at_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="centre_name">{{ trans('cruds.applicationForm.fields.centre_name') }}</label>
                            <input class="form-control" type="text" name="centre_name" id="centre_name" value="{{ old('centre_name', '') }}">
                            @if($errors->has('centre_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('centre_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.centre_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="centre_code">{{ trans('cruds.applicationForm.fields.centre_code') }}</label>
                            <input class="form-control" type="text" name="centre_code" id="centre_code" value="{{ old('centre_code', '') }}">
                            @if($errors->has('centre_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('centre_code') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.centre_code_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="centre_address">{{ trans('cruds.applicationForm.fields.centre_address') }}</label>
                            <input class="form-control" type="text" name="centre_address" id="centre_address" value="{{ old('centre_address', '') }}">
                            @if($errors->has('centre_address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('centre_address') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.centre_address_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="centre_city">{{ trans('cruds.applicationForm.fields.centre_city') }}</label>
                            <input class="form-control" type="text" name="centre_city" id="centre_city" value="{{ old('centre_city', '') }}">
                            @if($errors->has('centre_city'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('centre_city') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.centre_city_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="room_no">{{ trans('cruds.applicationForm.fields.room_no') }}</label>
                            <input class="form-control" type="text" name="room_no" id="room_no" value="{{ old('room_no', '') }}">
                            @if($errors->has('room_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('room_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.room_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="seat_no">{{ trans('cruds.applicationForm.fields.seat_no') }}</label>
                            <input class="form-control" type="text" name="seat_no" id="seat_no" value="{{ old('seat_no', '') }}">
                            @if($errors->has('seat_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('seat_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.applicationForm.fields.seat_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection