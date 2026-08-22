@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.show') }} {{ trans('cruds.academicQualification.title') }}
    </div>

    <div class="p-6">
        <div class="mb-4">
            <div class="mb-4">
                <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('admin.academic-qualifications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class=\"w-full text-left text-sm text-gray-500 dark:text-gray-400\">
                <tbody>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.id') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->id }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.name') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.course') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->course }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.board') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->board->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.year') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->year }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.division') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ App\Models\AcademicQualification::DIVISION_SELECT[$academicQualification->division] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.percentage') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->percentage }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.cgpa') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->cgpa }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.subjects') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->subjects }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.title') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->title }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.remarks') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->remarks }}
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.document') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            @if($academicQualification->document)
                                <a href="{{ $academicQualification->document->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.academicQualification.fields.user') }}
                        </th>
                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                            {{ $academicQualification->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-4">
                <a class="inline-flex rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" href="{{ route('admin.academic-qualifications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection