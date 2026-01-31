@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.traed.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.traeds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.id') }}
                        </th>
                        <td>
                            {{ $traed->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.user') }}
                        </th>
                        <td>
                            {{ $traed->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.teaching_at_bachelors_level_in_years') }}
                        </th>
                        <td>
                            {{ $traed->teaching_at_bachelors_level_in_years }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.teaching_at_masters_level_in_years') }}
                        </th>
                        <td>
                            {{ $traed->teaching_at_masters_level_in_years }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.research_at_masters_level_in_years') }}
                        </th>
                        <td>
                            {{ $traed->research_at_masters_level_in_years }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.research_at_post_doctorals_level_in_years') }}
                        </th>
                        <td>
                            {{ $traed->research_at_post_doctorals_level_in_years }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.experience_in_educational_administration_in_years') }}
                        </th>
                        <td>
                            {{ $traed->experience_in_educational_administration_in_years }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.traed.fields.any_other_administrative_experience_in_years') }}
                        </th>
                        <td>
                            {{ $traed->any_other_administrative_experience_in_years }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.traeds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection