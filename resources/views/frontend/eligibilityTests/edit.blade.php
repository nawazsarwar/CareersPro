@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.eligibilityTest.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.eligibility-tests.update", [$eligibilityTest->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.eligibilityTest.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $eligibilityTest->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eligibilityTest.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="agency">{{ trans('cruds.eligibilityTest.fields.agency') }}</label>
                            <input class="form-control" type="text" name="agency" id="agency" value="{{ old('agency', $eligibilityTest->agency) }}" required>
                            @if($errors->has('agency'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('agency') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eligibilityTest.fields.agency_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="year">{{ trans('cruds.eligibilityTest.fields.year') }}</label>
                            <input class="form-control date" type="text" name="year" id="year" value="{{ old('year', $eligibilityTest->year) }}" required>
                            @if($errors->has('year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eligibilityTest.fields.year_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="subject">{{ trans('cruds.eligibilityTest.fields.subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="subject" value="{{ old('subject', $eligibilityTest->subject) }}" required>
                            @if($errors->has('subject'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eligibilityTest.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.eligibilityTest.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $eligibilityTest->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.eligibilityTest.fields.user_helper') }}</span>
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