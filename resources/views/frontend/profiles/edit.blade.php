@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.profile.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.profiles.update", [$profile->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.profile.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $profile->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="first_name">{{ trans('cruds.profile.fields.first_name') }}</label>
                            <input class="form-control" type="text" name="first_name" id="first_name" value="{{ old('first_name', $profile->first_name) }}" required>
                            @if($errors->has('first_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('first_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.first_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">{{ trans('cruds.profile.fields.middle_name') }}</label>
                            <input class="form-control" type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $profile->middle_name) }}">
                            @if($errors->has('middle_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('middle_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.middle_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="last_name">{{ trans('cruds.profile.fields.last_name') }}</label>
                            <input class="form-control" type="text" name="last_name" id="last_name" value="{{ old('last_name', $profile->last_name) }}">
                            @if($errors->has('last_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('last_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.last_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="spouse_name">{{ trans('cruds.profile.fields.spouse_name') }}</label>
                            <input class="form-control" type="text" name="spouse_name" id="spouse_name" value="{{ old('spouse_name', $profile->spouse_name) }}">
                            @if($errors->has('spouse_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('spouse_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.spouse_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="marital_status_id">{{ trans('cruds.profile.fields.marital_status') }}</label>
                            <select class="form-control select2" name="marital_status_id" id="marital_status_id">
                                @foreach($marital_statuses as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('marital_status_id') ? old('marital_status_id') : $profile->marital_status->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('marital_status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('marital_status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.marital_status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fathers_name">{{ trans('cruds.profile.fields.fathers_name') }}</label>
                            <input class="form-control" type="text" name="fathers_name" id="fathers_name" value="{{ old('fathers_name', $profile->fathers_name) }}">
                            @if($errors->has('fathers_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fathers_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.fathers_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mothers_name">{{ trans('cruds.profile.fields.mothers_name') }}</label>
                            <input class="form-control" type="text" name="mothers_name" id="mothers_name" value="{{ old('mothers_name', $profile->mothers_name) }}">
                            @if($errors->has('mothers_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mothers_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.mothers_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="dob">{{ trans('cruds.profile.fields.dob') }}</label>
                            <input class="form-control date" type="text" name="dob" id="dob" value="{{ old('dob', $profile->dob) }}">
                            @if($errors->has('dob'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dob') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.dob_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.profile.fields.gender') }}</label>
                            <select class="form-control" name="gender" id="gender">
                                <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Profile::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('gender', $profile->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gender') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.gender_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mobile">{{ trans('cruds.profile.fields.mobile') }}</label>
                            <input class="form-control" type="text" name="mobile" id="mobile" value="{{ old('mobile', $profile->mobile) }}">
                            @if($errors->has('mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.mobile_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mobile_verified_at">{{ trans('cruds.profile.fields.mobile_verified_at') }}</label>
                            <input class="form-control datetime" type="text" name="mobile_verified_at" id="mobile_verified_at" value="{{ old('mobile_verified_at', $profile->mobile_verified_at) }}">
                            @if($errors->has('mobile_verified_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile_verified_at') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.mobile_verified_at_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="alternate_mobile">{{ trans('cruds.profile.fields.alternate_mobile') }}</label>
                            <input class="form-control" type="text" name="alternate_mobile" id="alternate_mobile" value="{{ old('alternate_mobile', $profile->alternate_mobile) }}">
                            @if($errors->has('alternate_mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('alternate_mobile') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.alternate_mobile_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.profile.fields.pwd') }}</label>
                            <select class="form-control" name="pwd" id="pwd">
                                <option value disabled {{ old('pwd', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Profile::PWD_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('pwd', $profile->pwd) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('pwd'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pwd') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.pwd_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="disability_type_id">{{ trans('cruds.profile.fields.disability_type') }}</label>
                            <select class="form-control select2" name="disability_type_id" id="disability_type_id">
                                @foreach($disability_types as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('disability_type_id') ? old('disability_type_id') : $profile->disability_type->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('disability_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('disability_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.disability_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="disability_percent">{{ trans('cruds.profile.fields.disability_percent') }}</label>
                            <input class="form-control" type="number" name="disability_percent" id="disability_percent" value="{{ old('disability_percent', $profile->disability_percent) }}" step="1">
                            @if($errors->has('disability_percent'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('disability_percent') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.disability_percent_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="aadhaar_no">{{ trans('cruds.profile.fields.aadhaar_no') }}</label>
                            <input class="form-control" type="text" name="aadhaar_no" id="aadhaar_no" value="{{ old('aadhaar_no', $profile->aadhaar_no) }}">
                            @if($errors->has('aadhaar_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('aadhaar_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.aadhaar_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="religion_id">{{ trans('cruds.profile.fields.religion') }}</label>
                            <select class="form-control select2" name="religion_id" id="religion_id">
                                @foreach($religions as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('religion_id') ? old('religion_id') : $profile->religion->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('religion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('religion') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.religion_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="category_id">{{ trans('cruds.profile.fields.category') }}</label>
                            <select class="form-control select2" name="category_id" id="category_id">
                                @foreach($categories as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $profile->category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('category') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.category_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="caste_id">{{ trans('cruds.profile.fields.caste') }}</label>
                            <select class="form-control select2" name="caste_id" id="caste_id">
                                @foreach($castes as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('caste_id') ? old('caste_id') : $profile->caste->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('caste'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('caste') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.caste_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sub_caste">{{ trans('cruds.profile.fields.sub_caste') }}</label>
                            <input class="form-control" type="text" name="sub_caste" id="sub_caste" value="{{ old('sub_caste', $profile->sub_caste) }}">
                            @if($errors->has('sub_caste'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sub_caste') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.sub_caste_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="nationality_id">{{ trans('cruds.profile.fields.nationality') }}</label>
                            <select class="form-control select2" name="nationality_id" id="nationality_id">
                                @foreach($nationalities as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('nationality_id') ? old('nationality_id') : $profile->nationality->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('nationality'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nationality') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.nationality_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.profile.fields.place_of_birth') }}</label>
                            <select class="form-control" name="place_of_birth" id="place_of_birth">
                                <option value disabled {{ old('place_of_birth', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Profile::PLACE_OF_BIRTH_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('place_of_birth', $profile->place_of_birth) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('place_of_birth'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('place_of_birth') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.place_of_birth_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="district_of_birth_id">{{ trans('cruds.profile.fields.district_of_birth') }}</label>
                            <select class="form-control select2" name="district_of_birth_id" id="district_of_birth_id">
                                @foreach($district_of_births as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('district_of_birth_id') ? old('district_of_birth_id') : $profile->district_of_birth->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('district_of_birth'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('district_of_birth') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.district_of_birth_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="state_of_birth_id">{{ trans('cruds.profile.fields.state_of_birth') }}</label>
                            <select class="form-control select2" name="state_of_birth_id" id="state_of_birth_id">
                                @foreach($state_of_births as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('state_of_birth_id') ? old('state_of_birth_id') : $profile->state_of_birth->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('state_of_birth'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('state_of_birth') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.state_of_birth_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="domicile_state_id">{{ trans('cruds.profile.fields.domicile_state') }}</label>
                            <select class="form-control select2" name="domicile_state_id" id="domicile_state_id">
                                @foreach($domicile_states as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('domicile_state_id') ? old('domicile_state_id') : $profile->domicile_state->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domicile_state'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('domicile_state') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.domicile_state_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="domicile_district_id">{{ trans('cruds.profile.fields.domicile_district') }}</label>
                            <select class="form-control select2" name="domicile_district_id" id="domicile_district_id">
                                @foreach($domicile_districts as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('domicile_district_id') ? old('domicile_district_id') : $profile->domicile_district->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domicile_district'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('domicile_district') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.domicile_district_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="identity_marks">{{ trans('cruds.profile.fields.identity_marks') }}</label>
                            <input class="form-control" type="text" name="identity_marks" id="identity_marks" value="{{ old('identity_marks', $profile->identity_marks) }}">
                            @if($errors->has('identity_marks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('identity_marks') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.identity_marks_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ trans('cruds.profile.fields.remarks') }}</label>
                            <textarea class="form-control" name="remarks" id="remarks">{{ old('remarks', $profile->remarks) }}</textarea>
                            @if($errors->has('remarks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remarks') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.remarks_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="verified">{{ trans('cruds.profile.fields.verified') }}</label>
                            <input class="form-control" type="number" name="verified" id="verified" value="{{ old('verified', $profile->verified) }}" step="1" required>
                            @if($errors->has('verified'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('verified') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.verified_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="locked">{{ trans('cruds.profile.fields.locked') }}</label>
                            <input class="form-control" type="number" name="locked" id="locked" value="{{ old('locked', $profile->locked) }}" step="1" required>
                            @if($errors->has('locked'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('locked') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.locked_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.profile.fields.conviction') }}</label>
                            @foreach(App\Models\Profile::CONVICTION_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="conviction_{{ $key }}" name="conviction" value="{{ $key }}" {{ old('conviction', $profile->conviction) === (string) $key ? 'checked' : '' }}>
                                    <label for="conviction_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('conviction'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('conviction') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.conviction_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="conviction_reason">{{ trans('cruds.profile.fields.conviction_reason') }}</label>
                            <textarea class="form-control" name="conviction_reason" id="conviction_reason">{{ old('conviction_reason', $profile->conviction_reason) }}</textarea>
                            @if($errors->has('conviction_reason'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('conviction_reason') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.conviction_reason_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.profile.fields.debarred') }}</label>
                            @foreach(App\Models\Profile::DEBARRED_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="debarred_{{ $key }}" name="debarred" value="{{ $key }}" {{ old('debarred', $profile->debarred) === (string) $key ? 'checked' : '' }}>
                                    <label for="debarred_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('debarred'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('debarred') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.debarred_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="debarred_reason">{{ trans('cruds.profile.fields.debarred_reason') }}</label>
                            <textarea class="form-control" name="debarred_reason" id="debarred_reason">{{ old('debarred_reason', $profile->debarred_reason) }}</textarea>
                            @if($errors->has('debarred_reason'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('debarred_reason') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.debarred_reason_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.profile.fields.vigilance') }}</label>
                            @foreach(App\Models\Profile::VIGILANCE_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="vigilance_{{ $key }}" name="vigilance" value="{{ $key }}" {{ old('vigilance', $profile->vigilance) === (string) $key ? 'checked' : '' }}>
                                    <label for="vigilance_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('vigilance'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('vigilance') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.vigilance_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="vigilance_reason">{{ trans('cruds.profile.fields.vigilance_reason') }}</label>
                            <textarea class="form-control" name="vigilance_reason" id="vigilance_reason">{{ old('vigilance_reason', $profile->vigilance_reason) }}</textarea>
                            @if($errors->has('vigilance_reason'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('vigilance_reason') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.profile.fields.vigilance_reason_helper') }}</span>
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