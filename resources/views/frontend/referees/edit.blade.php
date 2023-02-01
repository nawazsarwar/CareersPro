@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.referee.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.referees.update", [$referee->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.referee.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $referee->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.referee.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="designation">{{ trans('cruds.referee.fields.designation') }}</label>
                            <input class="form-control" type="text" name="designation" id="designation" value="{{ old('designation', $referee->designation) }}" required>
                            @if($errors->has('designation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('designation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.referee.fields.designation_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="mobile">{{ trans('cruds.referee.fields.mobile') }}</label>
                            <input class="form-control" type="text" name="mobile" id="mobile" value="{{ old('mobile', $referee->mobile) }}" required>
                            @if($errors->has('mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.referee.fields.mobile_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="email">{{ trans('cruds.referee.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $referee->email) }}" required>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.referee.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="address">{{ trans('cruds.referee.fields.address') }}</label>
                            <textarea class="form-control" name="address" id="address" required>{{ old('address', $referee->address) }}</textarea>
                            @if($errors->has('address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.referee.fields.address_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="period_known">{{ trans('cruds.referee.fields.period_known') }}</label>
                            <input class="form-control" type="text" name="period_known" id="period_known" value="{{ old('period_known', $referee->period_known) }}" required>
                            @if($errors->has('period_known'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('period_known') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection