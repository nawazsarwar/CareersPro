@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.province.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.provinces.update", [$province->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="mb-4">
                            <label for="type">{{ trans('cruds.province.fields.type') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="type" id="type" value="{{ old('type', $province->type) }}">
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.type_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="name">{{ trans('cruds.province.fields.name') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="name" id="name" value="{{ old('name', $province->name) }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.name_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="iso_3166_2_in">{{ trans('cruds.province.fields.iso_3166_2_in') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="iso_3166_2_in" id="iso_3166_2_in" value="{{ old('iso_3166_2_in', $province->iso_3166_2_in) }}">
                            @if($errors->has('iso_3166_2_in'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('iso_3166_2_in') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.iso_3166_2_in_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="vehicle_code">{{ trans('cruds.province.fields.vehicle_code') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="vehicle_code" id="vehicle_code" value="{{ old('vehicle_code', $province->vehicle_code) }}">
                            @if($errors->has('vehicle_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('vehicle_code') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.vehicle_code_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="zone">{{ trans('cruds.province.fields.zone') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="zone" id="zone" value="{{ old('zone', $province->zone) }}">
                            @if($errors->has('zone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('zone') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.zone_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="capital">{{ trans('cruds.province.fields.capital') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="capital" id="capital" value="{{ old('capital', $province->capital) }}">
                            @if($errors->has('capital'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('capital') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.capital_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="largest_city">{{ trans('cruds.province.fields.largest_city') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="largest_city" id="largest_city" value="{{ old('largest_city', $province->largest_city) }}">
                            @if($errors->has('largest_city'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('largest_city') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.largest_city_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="statehood">{{ trans('cruds.province.fields.statehood') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="number" name="statehood" id="statehood" value="{{ old('statehood', $province->statehood) }}" step="1">
                            @if($errors->has('statehood'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('statehood') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.statehood_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="population">{{ trans('cruds.province.fields.population') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="population" id="population" value="{{ old('population', $province->population) }}">
                            @if($errors->has('population'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('population') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.population_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="area">{{ trans('cruds.province.fields.area') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="area" id="area" value="{{ old('area', $province->area) }}">
                            @if($errors->has('area'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('area') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.area_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="official_languages">{{ trans('cruds.province.fields.official_languages') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="official_languages" id="official_languages" value="{{ old('official_languages', $province->official_languages) }}">
                            @if($errors->has('official_languages'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('official_languages') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.province.fields.official_languages_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="additional_official_languages">{{ trans('cruds.province.fields.additional_official_languages') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="additional_official_languages" id="additional_official_languages" value="{{ old('additional_official_languages', $province->additional_official_languages) }}">
                            @if($errors->has('additional_official_languages'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('additional_official_languages') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection