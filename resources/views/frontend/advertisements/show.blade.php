@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.advertisement.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.advertisements.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.dated') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->dated }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.type') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->type->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.advertisement_url') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->advertisement_url }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.document') }}
                                    </th>
                                    <td>
                                        @if($advertisement->document)
                                            <a href="{{ $advertisement->document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.default_fee') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->default_fee }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.default_open_date') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->default_open_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.default_end_date') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->default_end_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.default_payment_end_date') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->default_payment_end_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.approved_at') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->approved_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.status') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.added_by') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->added_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.advertisement.fields.approved_by') }}
                                    </th>
                                    <td>
                                        {{ $advertisement->approved_by->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.advertisements.index') }}">
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