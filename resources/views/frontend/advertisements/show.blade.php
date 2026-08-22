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
                    <div class="mb-4">
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.advertisements.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <w-full text-left text-sm text-gray-500 dark:text-gray-400 class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <tbody>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.id') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.title') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.slug') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.description') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.dated') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->dated }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.type') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->type->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.advertisement_url') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->advertisement_url }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.document') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        @if($advertisement->document)
                                            <a href="{{ $advertisement->document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.default_fee') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->default_fee }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.default_open_date') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->default_open_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.default_end_date') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->default_end_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.default_payment_end_date') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->default_payment_end_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.approved_at') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->approved_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.status') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.remarks') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.added_by') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->added_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.advertisement.fields.approved_by') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $advertisement->approved_by->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </w-full text-left text-sm text-gray-500 dark:text-gray-400>
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.advertisements.index') }}">
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