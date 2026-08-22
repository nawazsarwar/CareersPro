@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.edit') }} {{ trans('cruds.profile.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.profiles.update", [$profile->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label class="required" for="user_id">{{ trans('cruds.profile.fields.user') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $profile->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-error-500">{{ $errors->first('user') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.user_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="first_name">{{ trans('cruds.profile.fields.first_name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name', $profile->first_name) }}" required>
                @if($errors->has('first_name'))
                    <span class="text-error-500">{{ $errors->first('first_name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.first_name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="middle_name">{{ trans('cruds.profile.fields.middle_name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('middle_name') ? 'is-invalid' : '' }}" type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $profile->middle_name) }}">
                @if($errors->has('middle_name'))
                    <span class="text-error-500">{{ $errors->first('middle_name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.middle_name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="last_name">{{ trans('cruds.profile.fields.last_name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $profile->last_name) }}">
                @if($errors->has('last_name'))
                    <span class="text-error-500">{{ $errors->first('last_name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.last_name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="spouse_name">{{ trans('cruds.profile.fields.spouse_name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('spouse_name') ? 'is-invalid' : '' }}" type="text" name="spouse_name" id="spouse_name" value="{{ old('spouse_name', $profile->spouse_name) }}">
                @if($errors->has('spouse_name'))
                    <span class="text-error-500">{{ $errors->first('spouse_name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.spouse_name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="marital_status_id">{{ trans('cruds.profile.fields.marital_status') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('marital_status') ? 'is-invalid' : '' }}" name="marital_status_id" id="marital_status_id">
                    @foreach($marital_statuses as $id => $entry)
                        <option value="{{ $id }}" {{ (old('marital_status_id') ? old('marital_status_id') : $profile->marital_status->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('marital_status'))
                    <span class="text-error-500">{{ $errors->first('marital_status') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.marital_status_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="fathers_name">{{ trans('cruds.profile.fields.fathers_name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('fathers_name') ? 'is-invalid' : '' }}" type="text" name="fathers_name" id="fathers_name" value="{{ old('fathers_name', $profile->fathers_name) }}">
                @if($errors->has('fathers_name'))
                    <span class="text-error-500">{{ $errors->first('fathers_name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.fathers_name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="mothers_name">{{ trans('cruds.profile.fields.mothers_name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('mothers_name') ? 'is-invalid' : '' }}" type="text" name="mothers_name" id="mothers_name" value="{{ old('mothers_name', $profile->mothers_name) }}">
                @if($errors->has('mothers_name'))
                    <span class="text-error-500">{{ $errors->first('mothers_name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.mothers_name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="dob">{{ trans('cruds.profile.fields.dob') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date {{ $errors->has('dob') ? 'is-invalid' : '' }}" type="text" name="dob" id="dob" value="{{ old('dob', $profile->dob) }}">
                @if($errors->has('dob'))
                    <span class="text-error-500">{{ $errors->first('dob') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.dob_helper') }}</span>
            </div>
            <div class="mb-4">
                <label>{{ trans('cruds.profile.fields.gender') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender">
                    <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Profile::GENDER_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('gender', $profile->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('gender'))
                    <span class="text-error-500">{{ $errors->first('gender') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.gender_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="mobile">{{ trans('cruds.profile.fields.mobile') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="text" name="mobile" id="mobile" value="{{ old('mobile', $profile->mobile) }}">
                @if($errors->has('mobile'))
                    <span class="text-error-500">{{ $errors->first('mobile') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.mobile_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="mobile_verified_at">{{ trans('cruds.profile.fields.mobile_verified_at') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 datetime {{ $errors->has('mobile_verified_at') ? 'is-invalid' : '' }}" type="text" name="mobile_verified_at" id="mobile_verified_at" value="{{ old('mobile_verified_at', $profile->mobile_verified_at) }}">
                @if($errors->has('mobile_verified_at'))
                    <span class="text-error-500">{{ $errors->first('mobile_verified_at') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.mobile_verified_at_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="alternate_mobile">{{ trans('cruds.profile.fields.alternate_mobile') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('alternate_mobile') ? 'is-invalid' : '' }}" type="text" name="alternate_mobile" id="alternate_mobile" value="{{ old('alternate_mobile', $profile->alternate_mobile) }}">
                @if($errors->has('alternate_mobile'))
                    <span class="text-error-500">{{ $errors->first('alternate_mobile') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.alternate_mobile_helper') }}</span>
            </div>
            <div class="mb-4">
                <label>{{ trans('cruds.profile.fields.pwd') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('pwd') ? 'is-invalid' : '' }}" name="pwd" id="pwd">
                    <option value disabled {{ old('pwd', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Profile::PWD_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('pwd', $profile->pwd) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('pwd'))
                    <span class="text-error-500">{{ $errors->first('pwd') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.pwd_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="disability_type_id">{{ trans('cruds.profile.fields.disability_type') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('disability_type') ? 'is-invalid' : '' }}" name="disability_type_id" id="disability_type_id">
                    @foreach($disability_types as $id => $entry)
                        <option value="{{ $id }}" {{ (old('disability_type_id') ? old('disability_type_id') : $profile->disability_type->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('disability_type'))
                    <span class="text-error-500">{{ $errors->first('disability_type') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.disability_type_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="disability_percent">{{ trans('cruds.profile.fields.disability_percent') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('disability_percent') ? 'is-invalid' : '' }}" type="number" name="disability_percent" id="disability_percent" value="{{ old('disability_percent', $profile->disability_percent) }}" step="1">
                @if($errors->has('disability_percent'))
                    <span class="text-error-500">{{ $errors->first('disability_percent') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.disability_percent_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="aadhaar_no">{{ trans('cruds.profile.fields.aadhaar_no') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('aadhaar_no') ? 'is-invalid' : '' }}" type="text" name="aadhaar_no" id="aadhaar_no" value="{{ old('aadhaar_no', $profile->aadhaar_no) }}">
                @if($errors->has('aadhaar_no'))
                    <span class="text-error-500">{{ $errors->first('aadhaar_no') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.aadhaar_no_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="religion_id">{{ trans('cruds.profile.fields.religion') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion_id" id="religion_id">
                    @foreach($religions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('religion_id') ? old('religion_id') : $profile->religion->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('religion'))
                    <span class="text-error-500">{{ $errors->first('religion') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.religion_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="category_id">{{ trans('cruds.profile.fields.category') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $profile->category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <span class="text-error-500">{{ $errors->first('category') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.category_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="caste_id">{{ trans('cruds.profile.fields.caste') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('caste') ? 'is-invalid' : '' }}" name="caste_id" id="caste_id">
                    @foreach($castes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('caste_id') ? old('caste_id') : $profile->caste->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('caste'))
                    <span class="text-error-500">{{ $errors->first('caste') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.caste_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="sub_caste">{{ trans('cruds.profile.fields.sub_caste') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('sub_caste') ? 'is-invalid' : '' }}" type="text" name="sub_caste" id="sub_caste" value="{{ old('sub_caste', $profile->sub_caste) }}">
                @if($errors->has('sub_caste'))
                    <span class="text-error-500">{{ $errors->first('sub_caste') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.sub_caste_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="nationality_id">{{ trans('cruds.profile.fields.nationality') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('nationality') ? 'is-invalid' : '' }}" name="nationality_id" id="nationality_id">
                    @foreach($nationalities as $id => $entry)
                        <option value="{{ $id }}" {{ (old('nationality_id') ? old('nationality_id') : $profile->nationality->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('nationality'))
                    <span class="text-error-500">{{ $errors->first('nationality') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.nationality_helper') }}</span>
            </div>
            <div class="mb-4">
                <label>{{ trans('cruds.profile.fields.place_of_birth') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('place_of_birth') ? 'is-invalid' : '' }}" name="place_of_birth" id="place_of_birth">
                    <option value disabled {{ old('place_of_birth', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Profile::PLACE_OF_BIRTH_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('place_of_birth', $profile->place_of_birth) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('place_of_birth'))
                    <span class="text-error-500">{{ $errors->first('place_of_birth') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.place_of_birth_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="district_of_birth_id">{{ trans('cruds.profile.fields.district_of_birth') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('district_of_birth') ? 'is-invalid' : '' }}" name="district_of_birth_id" id="district_of_birth_id">
                    @foreach($district_of_births as $id => $entry)
                        <option value="{{ $id }}" {{ (old('district_of_birth_id') ? old('district_of_birth_id') : $profile->district_of_birth->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('district_of_birth'))
                    <span class="text-error-500">{{ $errors->first('district_of_birth') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.district_of_birth_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="state_of_birth_id">{{ trans('cruds.profile.fields.state_of_birth') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('state_of_birth') ? 'is-invalid' : '' }}" name="state_of_birth_id" id="state_of_birth_id">
                    @foreach($state_of_births as $id => $entry)
                        <option value="{{ $id }}" {{ (old('state_of_birth_id') ? old('state_of_birth_id') : $profile->state_of_birth->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('state_of_birth'))
                    <span class="text-error-500">{{ $errors->first('state_of_birth') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.state_of_birth_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="domicile_state_id">{{ trans('cruds.profile.fields.domicile_state') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('domicile_state') ? 'is-invalid' : '' }}" name="domicile_state_id" id="domicile_state_id">
                    @foreach($domicile_states as $id => $entry)
                        <option value="{{ $id }}" {{ (old('domicile_state_id') ? old('domicile_state_id') : $profile->domicile_state->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('domicile_state'))
                    <span class="text-error-500">{{ $errors->first('domicile_state') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.domicile_state_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="domicile_district_id">{{ trans('cruds.profile.fields.domicile_district') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('domicile_district') ? 'is-invalid' : '' }}" name="domicile_district_id" id="domicile_district_id">
                    @foreach($domicile_districts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('domicile_district_id') ? old('domicile_district_id') : $profile->domicile_district->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('domicile_district'))
                    <span class="text-error-500">{{ $errors->first('domicile_district') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.domicile_district_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="identity_marks">{{ trans('cruds.profile.fields.identity_marks') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('identity_marks') ? 'is-invalid' : '' }}" type="text" name="identity_marks" id="identity_marks" value="{{ old('identity_marks', $profile->identity_marks) }}">
                @if($errors->has('identity_marks'))
                    <span class="text-error-500">{{ $errors->first('identity_marks') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.identity_marks_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="remarks">{{ trans('cruds.profile.fields.remarks') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks', $profile->remarks) }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-error-500">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.remarks_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="verified">{{ trans('cruds.profile.fields.verified') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('verified') ? 'is-invalid' : '' }}" type="number" name="verified" id="verified" value="{{ old('verified', $profile->verified) }}" step="1" required>
                @if($errors->has('verified'))
                    <span class="text-error-500">{{ $errors->first('verified') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.verified_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="locked">{{ trans('cruds.profile.fields.locked') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('locked') ? 'is-invalid' : '' }}" type="number" name="locked" id="locked" value="{{ old('locked', $profile->locked) }}" step="1" required>
                @if($errors->has('locked'))
                    <span class="text-error-500">{{ $errors->first('locked') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.locked_helper') }}</span>
            </div>
            <div class="mb-4">
                <label>{{ trans('cruds.profile.fields.conviction') }}</label>
                @foreach(App\Models\Profile::CONVICTION_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('conviction') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="conviction_{{ $key }}" name="conviction" value="{{ $key }}" {{ old('conviction', $profile->conviction) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="conviction_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('conviction'))
                    <span class="text-error-500">{{ $errors->first('conviction') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.conviction_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="conviction_reason">{{ trans('cruds.profile.fields.conviction_reason') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('conviction_reason') ? 'is-invalid' : '' }}" type="text" name="conviction_reason" id="conviction_reason" value="{{ old('conviction_reason', $profile->conviction_reason) }}">
                @if($errors->has('conviction_reason'))
                    <span class="text-error-500">{{ $errors->first('conviction_reason') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.conviction_reason_helper') }}</span>
            </div>
            <div class="mb-4">
                <label>{{ trans('cruds.profile.fields.debarred') }}</label>
                @foreach(App\Models\Profile::DEBARRED_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('debarred') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="debarred_{{ $key }}" name="debarred" value="{{ $key }}" {{ old('debarred', $profile->debarred) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="debarred_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('debarred'))
                    <span class="text-error-500">{{ $errors->first('debarred') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.debarred_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="debarred_reason">{{ trans('cruds.profile.fields.debarred_reason') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('debarred_reason') ? 'is-invalid' : '' }}" type="text" name="debarred_reason" id="debarred_reason" value="{{ old('debarred_reason', $profile->debarred_reason) }}">
                @if($errors->has('debarred_reason'))
                    <span class="text-error-500">{{ $errors->first('debarred_reason') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.debarred_reason_helper') }}</span>
            </div>
            <div class="mb-4">
                <label>{{ trans('cruds.profile.fields.vigilance') }}</label>
                @foreach(App\Models\Profile::VIGILANCE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('vigilance') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="vigilance_{{ $key }}" name="vigilance" value="{{ $key }}" {{ old('vigilance', $profile->vigilance) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="vigilance_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('vigilance'))
                    <span class="text-error-500">{{ $errors->first('vigilance') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.vigilance_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="vigilance_reason">{{ trans('cruds.profile.fields.vigilance_reason') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('vigilance_reason') ? 'is-invalid' : '' }}" type="text" name="vigilance_reason" id="vigilance_reason" value="{{ old('vigilance_reason', $profile->vigilance_reason) }}">
                @if($errors->has('vigilance_reason'))
                    <span class="text-error-500">{{ $errors->first('vigilance_reason') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.profile.fields.vigilance_reason_helper') }}</span>
            </div>
            <div class="mb-4">
                <button class="inline-flex rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-error-600" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection