@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.referee.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.referees.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.referee.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.referee.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.referee.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.referee.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="designation">{{ trans('cruds.referee.fields.designation') }}</label>
                <input class="form-control {{ $errors->has('designation') ? 'is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation', '') }}" required>
                @if($errors->has('designation'))
                    <span class="text-danger">{{ $errors->first('designation') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.referee.fields.designation_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mobile">{{ trans('cruds.referee.fields.mobile') }}</label>
                <input class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="text" name="mobile" id="mobile" value="{{ old('mobile', '') }}" required>
                @if($errors->has('mobile'))
                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.referee.fields.mobile_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.referee.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.referee.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="address">{{ trans('cruds.referee.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address" required>{{ old('address') }}</textarea>
                @if($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.referee.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="period_known">{{ trans('cruds.referee.fields.period_known') }}</label>
                <input class="form-control {{ $errors->has('period_known') ? 'is-invalid' : '' }}" type="text" name="period_known" id="period_known" value="{{ old('period_known', '') }}" required>
                @if($errors->has('period_known'))
                    <span class="text-danger">{{ $errors->first('period_known') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.referee.fields.period_known_helper') }}</span>
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