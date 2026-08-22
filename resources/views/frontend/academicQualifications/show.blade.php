@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.academicQualification.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.academic-qualifications.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->name->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.course') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->course }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.board') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->board->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.year') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->year }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.division') }}
                                    </th>
                                    <td>
                                        {{ App\Models\AcademicQualification::DIVISION_SELECT[$academicQualification->division] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.percentage') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->percentage }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.cgpa') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->cgpa }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.subjects') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->subjects }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $academicQualification->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.document') }}
                                    </th>
                                    <td>
                                        @if($academicQualification->document)
                                            <a href="{{ $academicQualification->document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.academic-qualifications.index') }}">
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