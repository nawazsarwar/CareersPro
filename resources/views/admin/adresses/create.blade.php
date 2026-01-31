@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.adress.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.adresses.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.adress.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.adress.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Adress::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="house_no">{{ trans('cruds.adress.fields.house_no') }}</label>
                <input class="form-control {{ $errors->has('house_no') ? 'is-invalid' : '' }}" type="text" name="house_no" id="house_no" value="{{ old('house_no', '') }}" required>
                @if($errors->has('house_no'))
                    <span class="text-danger">{{ $errors->first('house_no') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.house_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="street">{{ trans('cruds.adress.fields.street') }}</label>
                <input class="form-control {{ $errors->has('street') ? 'is-invalid' : '' }}" type="text" name="street" id="street" value="{{ old('street', '') }}" required>
                @if($errors->has('street'))
                    <span class="text-danger">{{ $errors->first('street') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.street_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="landmark">{{ trans('cruds.adress.fields.landmark') }}</label>
                <input class="form-control {{ $errors->has('landmark') ? 'is-invalid' : '' }}" type="text" name="landmark" id="landmark" value="{{ old('landmark', '') }}" required>
                @if($errors->has('landmark'))
                    <span class="text-danger">{{ $errors->first('landmark') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.landmark_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="locality">{{ trans('cruds.adress.fields.locality') }}</label>
                <input class="form-control {{ $errors->has('locality') ? 'is-invalid' : '' }}" type="text" name="locality" id="locality" value="{{ old('locality', '') }}">
                @if($errors->has('locality'))
                    <span class="text-danger">{{ $errors->first('locality') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.locality_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="city">{{ trans('cruds.adress.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', '') }}">
                @if($errors->has('city'))
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="postal_code_id">{{ trans('cruds.adress.fields.postal_code') }}</label>
                <select class="form-control select2 {{ $errors->has('postal_code') ? 'is-invalid' : '' }}" name="postal_code_id" id="postal_code_id" required>
                    @foreach($postal_codes as $id => $entry)
                        <option value="{{ $id }}" {{ old('postal_code_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('postal_code'))
                    <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.postal_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="district">{{ trans('cruds.adress.fields.district') }}</label>
                <input class="form-control {{ $errors->has('district') ? 'is-invalid' : '' }}" type="text" name="district" id="district" value="{{ old('district', '') }}" required>
                @if($errors->has('district'))
                    <span class="text-danger">{{ $errors->first('district') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.district_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="province_id">{{ trans('cruds.adress.fields.province') }}</label>
                <select class="form-control select2 {{ $errors->has('province') ? 'is-invalid' : '' }}" name="province_id" id="province_id" required>
                    @foreach($provinces as $id => $entry)
                        <option value="{{ $id }}" {{ old('province_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('province'))
                    <span class="text-danger">{{ $errors->first('province') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.province_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="country_id">{{ trans('cruds.adress.fields.country') }}</label>
                <select class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country_id" id="country_id" required>
                    @foreach($countries as $id => $entry)
                        <option value="{{ $id }}" {{ old('country_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="status">{{ trans('cruds.adress.fields.status') }}</label>
                <input class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" type="number" name="status" id="status" value="{{ old('status', '1') }}" step="1" required>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.adress.fields.remarks') }}</label>
                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.adress.fields.remarks_helper') }}</span>
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