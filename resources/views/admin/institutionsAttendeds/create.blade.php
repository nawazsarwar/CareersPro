@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.institutionsAttended.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.institutions-attendeds.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.institutionsAttended.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.institutionsAttended.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name_of_school">{{ trans('cruds.institutionsAttended.fields.name_of_school') }}</label>
                <input class="form-control {{ $errors->has('name_of_school') ? 'is-invalid' : '' }}" type="text" name="name_of_school" id="name_of_school" value="{{ old('name_of_school', '') }}" required>
                @if($errors->has('name_of_school'))
                    <span class="text-danger">{{ $errors->first('name_of_school') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.institutionsAttended.fields.name_of_school_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name_of_college">{{ trans('cruds.institutionsAttended.fields.name_of_college') }}</label>
                <input class="form-control {{ $errors->has('name_of_college') ? 'is-invalid' : '' }}" type="text" name="name_of_college" id="name_of_college" value="{{ old('name_of_college', '') }}" required>
                @if($errors->has('name_of_college'))
                    <span class="text-danger">{{ $errors->first('name_of_college') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.institutionsAttended.fields.name_of_college_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="university_board_id">{{ trans('cruds.institutionsAttended.fields.university_board') }}</label>
                <select class="form-control select2 {{ $errors->has('university_board') ? 'is-invalid' : '' }}" name="university_board_id" id="university_board_id" required>
                    @foreach($university_boards as $id => $entry)
                        <option value="{{ $id }}" {{ old('university_board_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('university_board'))
                    <span class="text-danger">{{ $errors->first('university_board') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.institutionsAttended.fields.university_board_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="year_of_joining">{{ trans('cruds.institutionsAttended.fields.year_of_joining') }}</label>
                <input class="form-control {{ $errors->has('year_of_joining') ? 'is-invalid' : '' }}" type="number" name="year_of_joining" id="year_of_joining" value="{{ old('year_of_joining', '') }}" step="1" required>
                @if($errors->has('year_of_joining'))
                    <span class="text-danger">{{ $errors->first('year_of_joining') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.institutionsAttended.fields.year_of_joining_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="year_of_leaving">{{ trans('cruds.institutionsAttended.fields.year_of_leaving') }}</label>
                <input class="form-control {{ $errors->has('year_of_leaving') ? 'is-invalid' : '' }}" type="number" name="year_of_leaving" id="year_of_leaving" value="{{ old('year_of_leaving', '') }}" step="1" required>
                @if($errors->has('year_of_leaving'))
                    <span class="text-danger">{{ $errors->first('year_of_leaving') }}</span>
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



@endsection