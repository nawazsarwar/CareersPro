@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.qualificationLevel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.qualification-levels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.qualificationLevel.fields.id') }}
                        </th>
                        <td>
                            {{ $qualificationLevel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.qualificationLevel.fields.name') }}
                        </th>
                        <td>
                            {{ $qualificationLevel->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.qualificationLevel.fields.value') }}
                        </th>
                        <td>
                            {{ $qualificationLevel->value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.qualificationLevel.fields.status') }}
                        </th>
                        <td>
                            {{ $qualificationLevel->status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.qualificationLevel.fields.remarks') }}
                        </th>
                        <td>
                            {{ $qualificationLevel->remarks }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.qualification-levels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection