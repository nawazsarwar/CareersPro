@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.photo.title') }}
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.photos.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <w-full text-left text-sm text-gray-500 dark:text-gray-400 class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <tbody>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.photo.fields.id') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $photo->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.photo.fields.photograph') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        @if($photo->photograph)
                                            <a href="{{ $photo->photograph->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $photo->photograph->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.photo.fields.signature') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        @if($photo->signature)
                                            <a href="{{ $photo->signature->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $photo->signature->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.photo.fields.thumb_impression') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        @if($photo->thumb_impression)
                                            <a href="{{ $photo->thumb_impression->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $photo->thumb_impression->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.photo.fields.user') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $photo->user->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </w-full text-left text-sm text-gray-500 dark:text-gray-400>
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.photos.index') }}">
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