@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.adress.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.adresses.update", [$adress->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.adress.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $adress->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.adress.fields.type') }}</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Adress::TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', $adress->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="house_no">{{ trans('cruds.adress.fields.house_no') }}</label>
                            <input class="form-control" type="text" name="house_no" id="house_no" value="{{ old('house_no', $adress->house_no) }}" required>
                            @if($errors->has('house_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('house_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.house_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="street">{{ trans('cruds.adress.fields.street') }}</label>
                            <input class="form-control" type="text" name="street" id="street" value="{{ old('street', $adress->street) }}" required>
                            @if($errors->has('street'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('street') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.street_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="landmark">{{ trans('cruds.adress.fields.landmark') }}</label>
                            <input class="form-control" type="text" name="landmark" id="landmark" value="{{ old('landmark', $adress->landmark) }}" required>
                            @if($errors->has('landmark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('landmark') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.landmark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="locality">{{ trans('cruds.adress.fields.locality') }}</label>
                            <input class="form-control" type="text" name="locality" id="locality" value="{{ old('locality', $adress->locality) }}">
                            @if($errors->has('locality'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('locality') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.locality_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="city">{{ trans('cruds.adress.fields.city') }}</label>
                            <input class="form-control" type="text" name="city" id="city" value="{{ old('city', $adress->city) }}">
                            @if($errors->has('city'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('city') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.city_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="postal_code_id">{{ trans('cruds.adress.fields.postal_code') }}</label>
                            <select class="form-control select2" name="postal_code_id" id="postal_code_id" required>
                                @foreach($postal_codes as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('postal_code_id') ? old('postal_code_id') : $adress->postal_code->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('postal_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('postal_code') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.postal_code_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="district">{{ trans('cruds.adress.fields.district') }}</label>
                            <input class="form-control" type="text" name="district" id="district" value="{{ old('district', $adress->district) }}" required>
                            @if($errors->has('district'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('district') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.district_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="province_id">{{ trans('cruds.adress.fields.province') }}</label>
                            <select class="form-control select2" name="province_id" id="province_id" required>
                                @foreach($provinces as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('province_id') ? old('province_id') : $adress->province->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('province'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('province') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.province_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="country_id">{{ trans('cruds.adress.fields.country') }}</label>
                            <select class="form-control select2" name="country_id" id="country_id" required>
                                @foreach($countries as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('country_id') ? old('country_id') : $adress->country->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('country'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('country') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.country_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="status">{{ trans('cruds.adress.fields.status') }}</label>
                            <input class="form-control" type="number" name="status" id="status" value="{{ old('status', $adress->status) }}" step="1" required>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.adress.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ trans('cruds.adress.fields.remarks') }}</label>
                            <textarea class="form-control" name="remarks" id="remarks">{{ old('remarks', $adress->remarks) }}</textarea>
                            @if($errors->has('remarks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remarks') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection