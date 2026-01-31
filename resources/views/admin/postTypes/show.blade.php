@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.postType.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.post-types.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.id') }}
                        </th>
                        <td>
                            {{ $postType->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.name') }}
                        </th>
                        <td>
                            {{ $postType->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.pdf_template') }}
                        </th>
                        <td>
                            {{ $postType->pdf_template }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.admit_card_template') }}
                        </th>
                        <td>
                            {{ $postType->admit_card_template }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.interview_letter_template') }}
                        </th>
                        <td>
                            {{ $postType->interview_letter_template }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.submission_venue') }}
                        </th>
                        <td>
                            {{ $postType->submission_venue }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.status') }}
                        </th>
                        <td>
                            {{ $postType->status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.postType.fields.remarks') }}
                        </th>
                        <td>
                            {{ $postType->remarks }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.post-types.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection