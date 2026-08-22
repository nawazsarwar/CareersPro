@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.institutionsAttended.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.institutions-attendeds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.institutionsAttended.fields.id') }}
                        </th>
                        <td>
                            {{ $institutionsAttended->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.institutionsAttended.fields.user') }}
                        </th>
                        <td>
                            {{ $institutionsAttended->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.institutionsAttended.fields.name_of_school') }}
                        </th>
                        <td>
                            {{ $institutionsAttended->name_of_school }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.institutionsAttended.fields.name_of_college') }}
                        </th>
                        <td>
                            {{ $institutionsAttended->name_of_college }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.institutionsAttended.fields.university_board') }}
                        </th>
                        <td>
                            {{ $institutionsAttended->university_board->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.institutionsAttended.fields.year_of_joining') }}
                        </th>
                        <td>
                            {{ $institutionsAttended->year_of_joining }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.institutionsAttended.fields.year_of_leaving') }}
                        </th>
                        <td>
                            {{ $institutionsAttended->year_of_leaving }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.institutions-attendeds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection