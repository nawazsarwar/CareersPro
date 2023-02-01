@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.workExperience.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.work-experiences.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.employer') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->employer }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\WorkExperience::TYPE_SELECT[$workExperience->type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.designation') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->designation }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.from') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->from }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.to') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.responsibilities') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->responsibilities }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.reason_for_leaving') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->reason_for_leaving }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.pay_band') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->pay_band }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.basic_pay') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->basic_pay }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.gross_pay') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->gross_pay }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $workExperience->user->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.work-experiences.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection