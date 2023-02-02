@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.photo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.photos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.id') }}
                        </th>
                        <td>
                            {{ $photo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.photograph') }}
                        </th>
                        <td>
                            @if($photo->photograph)
                                <a href="{{ $photo->photograph->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $photo->photograph->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.signature') }}
                        </th>
                        <td>
                            @if($photo->signature)
                                <a href="{{ $photo->signature->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $photo->signature->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.thumb_impression') }}
                        </th>
                        <td>
                            @if($photo->thumb_impression)
                                <a href="{{ $photo->thumb_impression->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $photo->thumb_impression->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.user') }}
                        </th>
                        <td>
                            {{ $photo->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.photos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection