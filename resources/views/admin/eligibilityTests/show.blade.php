@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.eligibilityTest.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.eligibility-tests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.eligibilityTest.fields.id') }}
                        </th>
                        <td>
                            {{ $eligibilityTest->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.eligibilityTest.fields.user') }}
                        </th>
                        <td>
                            {{ $eligibilityTest->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.eligibilityTest.fields.name') }}
                        </th>
                        <td>
                            {{ $eligibilityTest->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.eligibilityTest.fields.agency') }}
                        </th>
                        <td>
                            {{ $eligibilityTest->agency }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.eligibilityTest.fields.year') }}
                        </th>
                        <td>
                            {{ $eligibilityTest->year }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.eligibilityTest.fields.subject') }}
                        </th>
                        <td>
                            {{ $eligibilityTest->subject }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.eligibility-tests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection