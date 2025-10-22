@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.employmentHistory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.employment-histories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.id') }}
                        </th>
                        <td>
                            {{ $employmentHistory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.employer') }}
                        </th>
                        <td>
                            {{ $employmentHistory->employer }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\EmploymentHistory::TYPE_SELECT[$employmentHistory->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.designation') }}
                        </th>
                        <td>
                            {{ $employmentHistory->designation }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.from') }}
                        </th>
                        <td>
                            {{ $employmentHistory->from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.to') }}
                        </th>
                        <td>
                            {{ $employmentHistory->to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.responsibilities') }}
                        </th>
                        <td>
                            {{ $employmentHistory->responsibilities }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.reason_for_leaving') }}
                        </th>
                        <td>
                            {{ $employmentHistory->reason_for_leaving }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.pay_band') }}
                        </th>
                        <td>
                            {{ $employmentHistory->pay_band }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.basic_pay') }}
                        </th>
                        <td>
                            {{ $employmentHistory->basic_pay }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.gross_pay') }}
                        </th>
                        <td>
                            {{ $employmentHistory->gross_pay }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.employmentHistory.fields.user') }}
                        </th>
                        <td>
                            {{ $employmentHistory->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.employment-histories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection