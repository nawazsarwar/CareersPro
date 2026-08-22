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
                    <div class="mb-4">
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.provinces.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <w-full text-left text-sm text-gray-500 dark:text-gray-400 class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <tbody>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.id') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.type') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->type }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.name') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.iso_3166_2_in') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->iso_3166_2_in }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.vehicle_code') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->vehicle_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.zone') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->zone }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.capital') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->capital }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.largest_city') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->largest_city }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.statehood') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->statehood }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.population') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->population }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.area') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->area }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.official_languages') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->official_languages }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.additional_official_languages') }}
                                    </th>
                                    <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                        {{ $province->additional_official_languages }}
                                    </td>
                                </tr>
                            </tbody>
                        </w-full text-left text-sm text-gray-500 dark:text-gray-400>
                        <div class="mb-4">
                            <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('frontend.provinces.index') }}">
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