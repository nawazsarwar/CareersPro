@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.postalCode.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.postal-codes.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.postalCode.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postalCode.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="locality">{{ trans('cruds.postalCode.fields.locality') }}</label>
                <input class="form-control {{ $errors->has('locality') ? 'is-invalid' : '' }}" type="text" name="locality" id="locality" value="{{ old('locality', '') }}">
                @if($errors->has('locality'))
                    <span class="text-danger">{{ $errors->first('locality') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postalCode.fields.locality_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="code">{{ trans('cruds.postalCode.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="number" name="code" id="code" value="{{ old('code', '') }}" step="1" required>
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postalCode.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sub_district">{{ trans('cruds.postalCode.fields.sub_district') }}</label>
                <input class="form-control {{ $errors->has('sub_district') ? 'is-invalid' : '' }}" type="text" name="sub_district" id="sub_district" value="{{ old('sub_district', '') }}">
                @if($errors->has('sub_district'))
                    <span class="text-danger">{{ $errors->first('sub_district') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postalCode.fields.sub_district_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="district">{{ trans('cruds.postalCode.fields.district') }}</label>
                <input class="form-control {{ $errors->has('district') ? 'is-invalid' : '' }}" type="text" name="district" id="district" value="{{ old('district', '') }}" required>
                @if($errors->has('district'))
                    <span class="text-danger">{{ $errors->first('district') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postalCode.fields.district_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="province_id">{{ trans('cruds.postalCode.fields.province') }}</label>
                <select class="form-control select2 {{ $errors->has('province') ? 'is-invalid' : '' }}" name="province_id" id="province_id">
                    @foreach($provinces as $id => $entry)
                        <option value="{{ $id }}" {{ old('province_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('province'))
                    <span class="text-danger">{{ $errors->first('province') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postalCode.fields.province_helper') }}</span>
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