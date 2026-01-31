@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.institutionsAttended.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.institutions-attendeds.update", [$institutionsAttended->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.institutionsAttended.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $institutionsAttended->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.institutionsAttended.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="name_of_school">{{ trans('cruds.institutionsAttended.fields.name_of_school') }}</label>
                            <input class="form-control" type="text" name="name_of_school" id="name_of_school" value="{{ old('name_of_school', $institutionsAttended->name_of_school) }}" required>
                            @if($errors->has('name_of_school'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name_of_school') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.institutionsAttended.fields.name_of_school_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="name_of_college">{{ trans('cruds.institutionsAttended.fields.name_of_college') }}</label>
                            <input class="form-control" type="text" name="name_of_college" id="name_of_college" value="{{ old('name_of_college', $institutionsAttended->name_of_college) }}" required>
                            @if($errors->has('name_of_college'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name_of_college') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.institutionsAttended.fields.name_of_college_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="university_board_id">{{ trans('cruds.institutionsAttended.fields.university_board') }}</label>
                            <select class="form-control select2" name="university_board_id" id="university_board_id" required>
                                @foreach($university_boards as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('university_board_id') ? old('university_board_id') : $institutionsAttended->university_board->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('university_board'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('university_board') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.institutionsAttended.fields.university_board_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="year_of_joining">{{ trans('cruds.institutionsAttended.fields.year_of_joining') }}</label>
                            <input class="form-control" type="number" name="year_of_joining" id="year_of_joining" value="{{ old('year_of_joining', $institutionsAttended->year_of_joining) }}" step="1" required>
                            @if($errors->has('year_of_joining'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year_of_joining') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.institutionsAttended.fields.year_of_joining_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="year_of_leaving">{{ trans('cruds.institutionsAttended.fields.year_of_leaving') }}</label>
                            <input class="form-control" type="number" name="year_of_leaving" id="year_of_leaving" value="{{ old('year_of_leaving', $institutionsAttended->year_of_leaving) }}" step="1" required>
                            @if($errors->has('year_of_leaving'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year_of_leaving') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.institutionsAttended.fields.year_of_leaving_helper') }}</span>
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