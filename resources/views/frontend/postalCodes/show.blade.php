@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.postalCode.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.postal-codes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.postalCode.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $postalCode->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.postalCode.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $postalCode->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.postalCode.fields.locality') }}
                                    </th>
                                    <td>
                                        {{ $postalCode->locality }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.postalCode.fields.code') }}
                                    </th>
                                    <td>
                                        {{ $postalCode->code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.postalCode.fields.sub_district') }}
                                    </th>
                                    <td>
                                        {{ $postalCode->sub_district }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.postalCode.fields.district') }}
                                    </th>
                                    <td>
                                        {{ $postalCode->district }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.postalCode.fields.province') }}
                                    </th>
                                    <td>
                                        {{ $postalCode->province->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.postal-codes.index') }}">
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