@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.foreignVisit.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.foreign-visits.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.foreignVisit.fields.id') }}
                        </th>
                        <td>
                            {{ $foreignVisit->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.foreignVisit.fields.user') }}
                        </th>
                        <td>
                            {{ $foreignVisit->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.foreignVisit.fields.country') }}
                        </th>
                        <td>
                            {{ $foreignVisit->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.foreignVisit.fields.date') }}
                        </th>
                        <td>
                            {{ $foreignVisit->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.foreignVisit.fields.duration') }}
                        </th>
                        <td>
                            {{ $foreignVisit->duration }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.foreignVisit.fields.purpose') }}
                        </th>
                        <td>
                            {{ $foreignVisit->purpose }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.foreign-visits.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection