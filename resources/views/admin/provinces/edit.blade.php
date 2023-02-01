@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.province.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.provinces.update", [$province->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="type">{{ trans('cruds.province.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', $province->type) }}">
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.province.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $province->name) }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="iso_3166_2_in">{{ trans('cruds.province.fields.iso_3166_2_in') }}</label>
                <input class="form-control {{ $errors->has('iso_3166_2_in') ? 'is-invalid' : '' }}" type="text" name="iso_3166_2_in" id="iso_3166_2_in" value="{{ old('iso_3166_2_in', $province->iso_3166_2_in) }}">
                @if($errors->has('iso_3166_2_in'))
                    <span class="text-danger">{{ $errors->first('iso_3166_2_in') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.iso_3166_2_in_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="vehicle_code">{{ trans('cruds.province.fields.vehicle_code') }}</label>
                <input class="form-control {{ $errors->has('vehicle_code') ? 'is-invalid' : '' }}" type="text" name="vehicle_code" id="vehicle_code" value="{{ old('vehicle_code', $province->vehicle_code) }}">
                @if($errors->has('vehicle_code'))
                    <span class="text-danger">{{ $errors->first('vehicle_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.vehicle_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="zone">{{ trans('cruds.province.fields.zone') }}</label>
                <input class="form-control {{ $errors->has('zone') ? 'is-invalid' : '' }}" type="text" name="zone" id="zone" value="{{ old('zone', $province->zone) }}">
                @if($errors->has('zone'))
                    <span class="text-danger">{{ $errors->first('zone') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.zone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="capital">{{ trans('cruds.province.fields.capital') }}</label>
                <input class="form-control {{ $errors->has('capital') ? 'is-invalid' : '' }}" type="text" name="capital" id="capital" value="{{ old('capital', $province->capital) }}">
                @if($errors->has('capital'))
                    <span class="text-danger">{{ $errors->first('capital') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.capital_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="largest_city">{{ trans('cruds.province.fields.largest_city') }}</label>
                <input class="form-control {{ $errors->has('largest_city') ? 'is-invalid' : '' }}" type="text" name="largest_city" id="largest_city" value="{{ old('largest_city', $province->largest_city) }}">
                @if($errors->has('largest_city'))
                    <span class="text-danger">{{ $errors->first('largest_city') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.largest_city_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="statehood">{{ trans('cruds.province.fields.statehood') }}</label>
                <input class="form-control {{ $errors->has('statehood') ? 'is-invalid' : '' }}" type="number" name="statehood" id="statehood" value="{{ old('statehood', $province->statehood) }}" step="1">
                @if($errors->has('statehood'))
                    <span class="text-danger">{{ $errors->first('statehood') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.statehood_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="population">{{ trans('cruds.province.fields.population') }}</label>
                <input class="form-control {{ $errors->has('population') ? 'is-invalid' : '' }}" type="text" name="population" id="population" value="{{ old('population', $province->population) }}">
                @if($errors->has('population'))
                    <span class="text-danger">{{ $errors->first('population') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.population_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="area">{{ trans('cruds.province.fields.area') }}</label>
                <input class="form-control {{ $errors->has('area') ? 'is-invalid' : '' }}" type="text" name="area" id="area" value="{{ old('area', $province->area) }}">
                @if($errors->has('area'))
                    <span class="text-danger">{{ $errors->first('area') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.area_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="official_languages">{{ trans('cruds.province.fields.official_languages') }}</label>
                <input class="form-control {{ $errors->has('official_languages') ? 'is-invalid' : '' }}" type="text" name="official_languages" id="official_languages" value="{{ old('official_languages', $province->official_languages) }}">
                @if($errors->has('official_languages'))
                    <span class="text-danger">{{ $errors->first('official_languages') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.official_languages_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="additional_official_languages">{{ trans('cruds.province.fields.additional_official_languages') }}</label>
                <input class="form-control {{ $errors->has('additional_official_languages') ? 'is-invalid' : '' }}" type="text" name="additional_official_languages" id="additional_official_languages" value="{{ old('additional_official_languages', $province->additional_official_languages) }}">
                @if($errors->has('additional_official_languages'))
                    <span class="text-danger">{{ $errors->first('additional_official_languages') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.province.fields.additional_official_languages_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection