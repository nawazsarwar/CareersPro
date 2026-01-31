@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.traed.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.traeds.update", [$traed->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.traed.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $traed->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.traed.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="teaching_at_bachelors_level_in_years">{{ trans('cruds.traed.fields.teaching_at_bachelors_level_in_years') }}</label>
                <input class="form-control {{ $errors->has('teaching_at_bachelors_level_in_years') ? 'is-invalid' : '' }}" type="number" name="teaching_at_bachelors_level_in_years" id="teaching_at_bachelors_level_in_years" value="{{ old('teaching_at_bachelors_level_in_years', $traed->teaching_at_bachelors_level_in_years) }}" step="1">
                @if($errors->has('teaching_at_bachelors_level_in_years'))
                    <span class="text-danger">{{ $errors->first('teaching_at_bachelors_level_in_years') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.traed.fields.teaching_at_bachelors_level_in_years_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="teaching_at_masters_level_in_years">{{ trans('cruds.traed.fields.teaching_at_masters_level_in_years') }}</label>
                <input class="form-control {{ $errors->has('teaching_at_masters_level_in_years') ? 'is-invalid' : '' }}" type="number" name="teaching_at_masters_level_in_years" id="teaching_at_masters_level_in_years" value="{{ old('teaching_at_masters_level_in_years', $traed->teaching_at_masters_level_in_years) }}" step="1">
                @if($errors->has('teaching_at_masters_level_in_years'))
                    <span class="text-danger">{{ $errors->first('teaching_at_masters_level_in_years') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.traed.fields.teaching_at_masters_level_in_years_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_at_masters_level_in_years">{{ trans('cruds.traed.fields.research_at_masters_level_in_years') }}</label>
                <input class="form-control {{ $errors->has('research_at_masters_level_in_years') ? 'is-invalid' : '' }}" type="number" name="research_at_masters_level_in_years" id="research_at_masters_level_in_years" value="{{ old('research_at_masters_level_in_years', $traed->research_at_masters_level_in_years) }}" step="1">
                @if($errors->has('research_at_masters_level_in_years'))
                    <span class="text-danger">{{ $errors->first('research_at_masters_level_in_years') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.traed.fields.research_at_masters_level_in_years_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_at_post_doctorals_level_in_years">{{ trans('cruds.traed.fields.research_at_post_doctorals_level_in_years') }}</label>
                <input class="form-control {{ $errors->has('research_at_post_doctorals_level_in_years') ? 'is-invalid' : '' }}" type="number" name="research_at_post_doctorals_level_in_years" id="research_at_post_doctorals_level_in_years" value="{{ old('research_at_post_doctorals_level_in_years', $traed->research_at_post_doctorals_level_in_years) }}" step="1">
                @if($errors->has('research_at_post_doctorals_level_in_years'))
                    <span class="text-danger">{{ $errors->first('research_at_post_doctorals_level_in_years') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.traed.fields.research_at_post_doctorals_level_in_years_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="experience_in_educational_administration_in_years">{{ trans('cruds.traed.fields.experience_in_educational_administration_in_years') }}</label>
                <input class="form-control {{ $errors->has('experience_in_educational_administration_in_years') ? 'is-invalid' : '' }}" type="number" name="experience_in_educational_administration_in_years" id="experience_in_educational_administration_in_years" value="{{ old('experience_in_educational_administration_in_years', $traed->experience_in_educational_administration_in_years) }}" step="1">
                @if($errors->has('experience_in_educational_administration_in_years'))
                    <span class="text-danger">{{ $errors->first('experience_in_educational_administration_in_years') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.traed.fields.experience_in_educational_administration_in_years_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="any_other_administrative_experience_in_years">{{ trans('cruds.traed.fields.any_other_administrative_experience_in_years') }}</label>
                <input class="form-control {{ $errors->has('any_other_administrative_experience_in_years') ? 'is-invalid' : '' }}" type="number" name="any_other_administrative_experience_in_years" id="any_other_administrative_experience_in_years" value="{{ old('any_other_administrative_experience_in_years', $traed->any_other_administrative_experience_in_years) }}" step="1">
                @if($errors->has('any_other_administrative_experience_in_years'))
                    <span class="text-danger">{{ $errors->first('any_other_administrative_experience_in_years') }}</span>
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



@endsection