@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.traed.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.traeds.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.traed.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.traed.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="teaching_at_bachelors_level_in_years">{{ trans('cruds.traed.fields.teaching_at_bachelors_level_in_years') }}</label>
                            <input class="form-control" type="number" name="teaching_at_bachelors_level_in_years" id="teaching_at_bachelors_level_in_years" value="{{ old('teaching_at_bachelors_level_in_years', '') }}" step="1">
                            @if($errors->has('teaching_at_bachelors_level_in_years'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('teaching_at_bachelors_level_in_years') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.traed.fields.teaching_at_bachelors_level_in_years_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="teaching_at_masters_level_in_years">{{ trans('cruds.traed.fields.teaching_at_masters_level_in_years') }}</label>
                            <input class="form-control" type="number" name="teaching_at_masters_level_in_years" id="teaching_at_masters_level_in_years" value="{{ old('teaching_at_masters_level_in_years', '') }}" step="1">
                            @if($errors->has('teaching_at_masters_level_in_years'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('teaching_at_masters_level_in_years') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.traed.fields.teaching_at_masters_level_in_years_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_at_masters_level_in_years">{{ trans('cruds.traed.fields.research_at_masters_level_in_years') }}</label>
                            <input class="form-control" type="number" name="research_at_masters_level_in_years" id="research_at_masters_level_in_years" value="{{ old('research_at_masters_level_in_years', '') }}" step="1">
                            @if($errors->has('research_at_masters_level_in_years'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_at_masters_level_in_years') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.traed.fields.research_at_masters_level_in_years_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_at_post_doctorals_level_in_years">{{ trans('cruds.traed.fields.research_at_post_doctorals_level_in_years') }}</label>
                            <input class="form-control" type="number" name="research_at_post_doctorals_level_in_years" id="research_at_post_doctorals_level_in_years" value="{{ old('research_at_post_doctorals_level_in_years', '') }}" step="1">
                            @if($errors->has('research_at_post_doctorals_level_in_years'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_at_post_doctorals_level_in_years') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.traed.fields.research_at_post_doctorals_level_in_years_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="experience_in_educational_administration_in_years">{{ trans('cruds.traed.fields.experience_in_educational_administration_in_years') }}</label>
                            <input class="form-control" type="number" name="experience_in_educational_administration_in_years" id="experience_in_educational_administration_in_years" value="{{ old('experience_in_educational_administration_in_years', '') }}" step="1">
                            @if($errors->has('experience_in_educational_administration_in_years'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('experience_in_educational_administration_in_years') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.traed.fields.experience_in_educational_administration_in_years_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="any_other_administrative_experience_in_years">{{ trans('cruds.traed.fields.any_other_administrative_experience_in_years') }}</label>
                            <input class="form-control" type="number" name="any_other_administrative_experience_in_years" id="any_other_administrative_experience_in_years" value="{{ old('any_other_administrative_experience_in_years', '') }}" step="1">
                            @if($errors->has('any_other_administrative_experience_in_years'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('any_other_administrative_experience_in_years') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.traed.fields.any_other_administrative_experience_in_years_helper') }}</span>
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