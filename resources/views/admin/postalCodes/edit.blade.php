@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.edit') }} {{ trans('cruds.postalCode.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.postal-codes.update", [$postalCode->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label class="required" for="name">{{ trans('cruds.postalCode.fields.name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $postalCode->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-error-500">{{ $errors->first('name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postalCode.fields.name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="locality">{{ trans('cruds.postalCode.fields.locality') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('locality') ? 'is-invalid' : '' }}" type="text" name="locality" id="locality" value="{{ old('locality', $postalCode->locality) }}">
                @if($errors->has('locality'))
                    <span class="text-error-500">{{ $errors->first('locality') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postalCode.fields.locality_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="code">{{ trans('cruds.postalCode.fields.code') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('code') ? 'is-invalid' : '' }}" type="number" name="code" id="code" value="{{ old('code', $postalCode->code) }}" step="1" required>
                @if($errors->has('code'))
                    <span class="text-error-500">{{ $errors->first('code') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postalCode.fields.code_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="sub_district">{{ trans('cruds.postalCode.fields.sub_district') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('sub_district') ? 'is-invalid' : '' }}" type="text" name="sub_district" id="sub_district" value="{{ old('sub_district', $postalCode->sub_district) }}">
                @if($errors->has('sub_district'))
                    <span class="text-error-500">{{ $errors->first('sub_district') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postalCode.fields.sub_district_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="district">{{ trans('cruds.postalCode.fields.district') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('district') ? 'is-invalid' : '' }}" type="text" name="district" id="district" value="{{ old('district', $postalCode->district) }}" required>
                @if($errors->has('district'))
                    <span class="text-error-500">{{ $errors->first('district') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postalCode.fields.district_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="province_id">{{ trans('cruds.postalCode.fields.province') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('province') ? 'is-invalid' : '' }}" name="province_id" id="province_id">
                    @foreach($provinces as $id => $entry)
                        <option value="{{ $id }}" {{ (old('province_id') ? old('province_id') : $postalCode->province->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('province'))
                    <span class="text-error-500">{{ $errors->first('province') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postalCode.fields.province_helper') }}</span>
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