@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.post.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.posts.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="advertisement_id">{{ trans('cruds.post.fields.advertisement') }}</label>
                            <select class="form-control select2" name="advertisement_id" id="advertisement_id" required>
                                @foreach($advertisements as $id => $entry)
                                    <option value="{{ $id }}" {{ old('advertisement_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('advertisement'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('advertisement') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.advertisement_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="posttype_id">{{ trans('cruds.post.fields.posttype') }}</label>
                            <select class="form-control select2" name="posttype_id" id="posttype_id" required>
                                @foreach($posttypes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('posttype_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('posttype'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('posttype') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.posttype_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="serial_no">{{ trans('cruds.post.fields.serial_no') }}</label>
                            <input class="form-control" type="number" name="serial_no" id="serial_no" value="{{ old('serial_no', '') }}" step="1">
                            @if($errors->has('serial_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('serial_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.serial_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="title">{{ trans('cruds.post.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="slug">{{ trans('cruds.post.fields.slug') }}</label>
                            <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                            @if($errors->has('slug'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('slug') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.slug_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.post.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="vacancies">{{ trans('cruds.post.fields.vacancies') }}</label>
                            <input class="form-control" type="number" name="vacancies" id="vacancies" value="{{ old('vacancies', '') }}" step="1" required>
                            @if($errors->has('vacancies'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('vacancies') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.vacancies_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="location">{{ trans('cruds.post.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', '') }}" required>
                            @if($errors->has('location'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('location') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="pay_level">{{ trans('cruds.post.fields.pay_level') }}</label>
                            <input class="form-control" type="text" name="pay_level" id="pay_level" value="{{ old('pay_level', '') }}" required>
                            @if($errors->has('pay_level'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pay_level') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.pay_level_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="pay_range">{{ trans('cruds.post.fields.pay_range') }}</label>
                            <input class="form-control" type="text" name="pay_range" id="pay_range" value="{{ old('pay_range', '') }}" required>
                            @if($errors->has('pay_range'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pay_range') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.pay_range_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="fee">{{ trans('cruds.post.fields.fee') }}</label>
                            <input class="form-control" type="number" name="fee" id="fee" value="{{ old('fee', '') }}" step="0.01" required>
                            @if($errors->has('fee'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fee') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.fee_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="open_date">{{ trans('cruds.post.fields.open_date') }}</label>
                            <input class="form-control datetime" type="text" name="open_date" id="open_date" value="{{ old('open_date') }}">
                            @if($errors->has('open_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('open_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.open_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="last_date">{{ trans('cruds.post.fields.last_date') }}</label>
                            <input class="form-control datetime" type="text" name="last_date" id="last_date" value="{{ old('last_date') }}">
                            @if($errors->has('last_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('last_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.last_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="payment_last_date">{{ trans('cruds.post.fields.payment_last_date') }}</label>
                            <input class="form-control datetime" type="text" name="payment_last_date" id="payment_last_date" value="{{ old('payment_last_date') }}">
                            @if($errors->has('payment_last_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('payment_last_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.payment_last_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="withdrawn">{{ trans('cruds.post.fields.withdrawn') }}</label>
                            <input class="form-control" type="number" name="withdrawn" id="withdrawn" value="{{ old('withdrawn', '0') }}" step="1" required>
                            @if($errors->has('withdrawn'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('withdrawn') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.withdrawn_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="status">{{ trans('cruds.post.fields.status') }}</label>
                            <input class="form-control" type="number" name="status" id="status" value="{{ old('status', '1') }}" step="1" required>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ trans('cruds.post.fields.remarks') }}</label>
                            <textarea class="form-control" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                            @if($errors->has('remarks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remarks') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.remarks_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="added_by_id">{{ trans('cruds.post.fields.added_by') }}</label>
                            <select class="form-control select2" name="added_by_id" id="added_by_id" required>
                                @foreach($added_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ old('added_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('added_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('added_by') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.added_by_helper') }}</span>
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