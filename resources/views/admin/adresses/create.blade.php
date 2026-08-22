@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.adress.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.adresses.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="required">{{ trans('cruds.adress.fields.type') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Adress::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <span class="text-error-500">{{ $errors->first('type') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.type_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="house_no">{{ trans('cruds.adress.fields.house_no') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('house_no') ? 'is-invalid' : '' }}" type="text" name="house_no" id="house_no" value="{{ old('house_no', '') }}" required>
                @if($errors->has('house_no'))
                    <span class="text-error-500">{{ $errors->first('house_no') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.house_no_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="street">{{ trans('cruds.adress.fields.street') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('street') ? 'is-invalid' : '' }}" type="text" name="street" id="street" value="{{ old('street', '') }}" required>
                @if($errors->has('street'))
                    <span class="text-error-500">{{ $errors->first('street') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.street_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="landmark">{{ trans('cruds.adress.fields.landmark') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('landmark') ? 'is-invalid' : '' }}" type="text" name="landmark" id="landmark" value="{{ old('landmark', '') }}" required>
                @if($errors->has('landmark'))
                    <span class="text-error-500">{{ $errors->first('landmark') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.landmark_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="locality">{{ trans('cruds.adress.fields.locality') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('locality') ? 'is-invalid' : '' }}" type="text" name="locality" id="locality" value="{{ old('locality', '') }}">
                @if($errors->has('locality'))
                    <span class="text-error-500">{{ $errors->first('locality') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.locality_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="city">{{ trans('cruds.adress.fields.city') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', '') }}">
                @if($errors->has('city'))
                    <span class="text-error-500">{{ $errors->first('city') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.city_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="postal_code_id">{{ trans('cruds.adress.fields.postal_code') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('postal_code') ? 'is-invalid' : '' }}" name="postal_code_id" id="postal_code_id" required>
                    @foreach($postal_codes as $id => $entry)
                        <option value="{{ $id }}" {{ old('postal_code_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('postal_code'))
                    <span class="text-error-500">{{ $errors->first('postal_code') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.postal_code_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="district">{{ trans('cruds.adress.fields.district') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('district') ? 'is-invalid' : '' }}" type="text" name="district" id="district" value="{{ old('district', '') }}" required>
                @if($errors->has('district'))
                    <span class="text-error-500">{{ $errors->first('district') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.district_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="province_id">{{ trans('cruds.adress.fields.province') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('province') ? 'is-invalid' : '' }}" name="province_id" id="province_id" required>
                    @foreach($provinces as $id => $entry)
                        <option value="{{ $id }}" {{ old('province_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('province'))
                    <span class="text-error-500">{{ $errors->first('province') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.province_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="country_id">{{ trans('cruds.adress.fields.country') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country_id" id="country_id" required>
                    @foreach($countries as $id => $entry)
                        <option value="{{ $id }}" {{ old('country_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <span class="text-error-500">{{ $errors->first('country') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.country_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="status">{{ trans('cruds.adress.fields.status') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('status') ? 'is-invalid' : '' }}" type="number" name="status" id="status" value="{{ old('status', '1') }}" step="1" required>
                @if($errors->has('status'))
                    <span class="text-error-500">{{ $errors->first('status') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.status_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="remarks">{{ trans('cruds.adress.fields.remarks') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-error-500">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.remarks_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="user_id">{{ trans('cruds.adress.fields.user') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-error-500">{{ $errors->first('user') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.adress.fields.user_helper') }}</span>
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