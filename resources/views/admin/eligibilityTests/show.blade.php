@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.show') }} {{ trans('cruds.eligibilityTest.title') }}
    </div>

    <div class="p-6">
        <div class="mb-4">
            <div class="mb-4">
                <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('admin.eligibility-tests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class=\"w-full text-left text-sm text-gray-500 dark:text-gray-400\">
                <tbody>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.eligibilityTest.fields.id') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $eligibilityTest->id }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.eligibilityTest.fields.name') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $eligibilityTest->name }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.eligibilityTest.fields.agency') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $eligibilityTest->agency }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.eligibilityTest.fields.year') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $eligibilityTest->year }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.eligibilityTest.fields.subject') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $eligibilityTest->subject }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.eligibilityTest.fields.user') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $eligibilityTest->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-4">
                <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('admin.eligibility-tests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection