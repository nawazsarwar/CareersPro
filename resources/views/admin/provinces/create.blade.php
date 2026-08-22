@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.province.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.provinces.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="type">{{ trans('cruds.province.fields.type') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', '') }}">
                @if($errors->has('type'))
                    <span class="text-error-500">{{ $errors->first('type') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.type_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="name">{{ trans('cruds.province.fields.name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <span class="text-error-500">{{ $errors->first('name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="iso_3166_2_in">{{ trans('cruds.province.fields.iso_3166_2_in') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('iso_3166_2_in') ? 'is-invalid' : '' }}" type="text" name="iso_3166_2_in" id="iso_3166_2_in" value="{{ old('iso_3166_2_in', '') }}">
                @if($errors->has('iso_3166_2_in'))
                    <span class="text-error-500">{{ $errors->first('iso_3166_2_in') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.iso_3166_2_in_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="vehicle_code">{{ trans('cruds.province.fields.vehicle_code') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('vehicle_code') ? 'is-invalid' : '' }}" type="text" name="vehicle_code" id="vehicle_code" value="{{ old('vehicle_code', '') }}">
                @if($errors->has('vehicle_code'))
                    <span class="text-error-500">{{ $errors->first('vehicle_code') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.vehicle_code_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="zone">{{ trans('cruds.province.fields.zone') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('zone') ? 'is-invalid' : '' }}" type="text" name="zone" id="zone" value="{{ old('zone', '') }}">
                @if($errors->has('zone'))
                    <span class="text-error-500">{{ $errors->first('zone') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.zone_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="capital">{{ trans('cruds.province.fields.capital') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('capital') ? 'is-invalid' : '' }}" type="text" name="capital" id="capital" value="{{ old('capital', '') }}">
                @if($errors->has('capital'))
                    <span class="text-error-500">{{ $errors->first('capital') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.capital_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="largest_city">{{ trans('cruds.province.fields.largest_city') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('largest_city') ? 'is-invalid' : '' }}" type="text" name="largest_city" id="largest_city" value="{{ old('largest_city', '') }}">
                @if($errors->has('largest_city'))
                    <span class="text-error-500">{{ $errors->first('largest_city') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.largest_city_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="statehood">{{ trans('cruds.province.fields.statehood') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('statehood') ? 'is-invalid' : '' }}" type="number" name="statehood" id="statehood" value="{{ old('statehood', '') }}" step="1">
                @if($errors->has('statehood'))
                    <span class="text-error-500">{{ $errors->first('statehood') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.statehood_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="population">{{ trans('cruds.province.fields.population') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('population') ? 'is-invalid' : '' }}" type="text" name="population" id="population" value="{{ old('population', '') }}">
                @if($errors->has('population'))
                    <span class="text-error-500">{{ $errors->first('population') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.population_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="area">{{ trans('cruds.province.fields.area') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('area') ? 'is-invalid' : '' }}" type="text" name="area" id="area" value="{{ old('area', '') }}">
                @if($errors->has('area'))
                    <span class="text-error-500">{{ $errors->first('area') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.area_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="official_languages">{{ trans('cruds.province.fields.official_languages') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('official_languages') ? 'is-invalid' : '' }}" type="text" name="official_languages" id="official_languages" value="{{ old('official_languages', '') }}">
                @if($errors->has('official_languages'))
                    <span class="text-error-500">{{ $errors->first('official_languages') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.official_languages_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="additional_official_languages">{{ trans('cruds.province.fields.additional_official_languages') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('additional_official_languages') ? 'is-invalid' : '' }}" type="text" name="additional_official_languages" id="additional_official_languages" value="{{ old('additional_official_languages', '') }}">
                @if($errors->has('additional_official_languages'))
                    <span class="text-error-500">{{ $errors->first('additional_official_languages') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.additional_official_languages_helper') }}</span>
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