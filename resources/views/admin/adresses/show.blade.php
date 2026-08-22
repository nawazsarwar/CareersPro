@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.show') }} {{ trans('cruds.adress.title') }}
    </div>

    <div class="p-6">
        <div class="mb-4">
            <div class="mb-4">
                <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('admin.adresses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class=\"w-full text-left text-sm text-gray-500 dark:text-gray-400\">
                <tbody>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.id') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->id }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.type') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ App\Models\Adress::TYPE_SELECT[$adress->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.house_no') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->house_no }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.street') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->street }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.landmark') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->landmark }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.locality') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->locality }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.city') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->city }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.postal_code') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->postal_code->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.district') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->district }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.province') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->province->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.country') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.status') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->status }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.remarks') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->remarks }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.adress.fields.user') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $adress->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-4">
                <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('admin.adresses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection