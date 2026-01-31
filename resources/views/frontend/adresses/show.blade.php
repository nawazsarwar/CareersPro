@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.adress.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.adresses.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $adress->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $adress->user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Adress::TYPE_SELECT[$adress->type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.house_no') }}
                                    </th>
                                    <td>
                                        {{ $adress->house_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.street') }}
                                    </th>
                                    <td>
                                        {{ $adress->street }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.landmark') }}
                                    </th>
                                    <td>
                                        {{ $adress->landmark }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.locality') }}
                                    </th>
                                    <td>
                                        {{ $adress->locality }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.city') }}
                                    </th>
                                    <td>
                                        {{ $adress->city }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.postal_code') }}
                                    </th>
                                    <td>
                                        {{ $adress->postal_code->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.district') }}
                                    </th>
                                    <td>
                                        {{ $adress->district }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.province') }}
                                    </th>
                                    <td>
                                        {{ $adress->province->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.country') }}
                                    </th>
                                    <td>
                                        {{ $adress->country->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.status') }}
                                    </th>
                                    <td>
                                        {{ $adress->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.adress.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $adress->remarks }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.adresses.index') }}">
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