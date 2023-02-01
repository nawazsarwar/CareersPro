@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.province.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.provinces.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $province->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.type') }}
                                    </th>
                                    <td>
                                        {{ $province->type }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $province->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.iso_3166_2_in') }}
                                    </th>
                                    <td>
                                        {{ $province->iso_3166_2_in }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.vehicle_code') }}
                                    </th>
                                    <td>
                                        {{ $province->vehicle_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.zone') }}
                                    </th>
                                    <td>
                                        {{ $province->zone }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.capital') }}
                                    </th>
                                    <td>
                                        {{ $province->capital }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.largest_city') }}
                                    </th>
                                    <td>
                                        {{ $province->largest_city }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.statehood') }}
                                    </th>
                                    <td>
                                        {{ $province->statehood }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.population') }}
                                    </th>
                                    <td>
                                        {{ $province->population }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.area') }}
                                    </th>
                                    <td>
                                        {{ $province->area }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.official_languages') }}
                                    </th>
                                    <td>
                                        {{ $province->official_languages }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.additional_official_languages') }}
                                    </th>
                                    <td>
                                        {{ $province->additional_official_languages }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.provinces.index') }}">
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