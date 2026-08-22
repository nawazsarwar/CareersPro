@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.postType.title') }}
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.post-types.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <w-full text-left text-sm text-gray-500 dark:text-gray-400 class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <tbody>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.postType.fields.id') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $postType->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.postType.fields.name') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $postType->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.postType.fields.pdf_template') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $postType->pdf_template }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.postType.fields.submission_venue') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $postType->submission_venue }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.postType.fields.status') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $postType->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.postType.fields.remarks') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $postType->remarks }}
                                    </td>
                                </tr>
                            </tbody>
                        </w-full text-left text-sm text-gray-500 dark:text-gray-400>
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.post-types.index') }}">
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