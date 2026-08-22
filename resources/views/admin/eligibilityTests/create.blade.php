@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.eligibilityTest.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.eligibility-tests.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="required" for="name">{{ trans('cruds.eligibilityTest.fields.name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-error-500">{{ $errors->first('name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.eligibilityTest.fields.name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="agency">{{ trans('cruds.eligibilityTest.fields.agency') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('agency') ? 'is-invalid' : '' }}" type="text" name="agency" id="agency" value="{{ old('agency', '') }}" required>
                @if($errors->has('agency'))
                    <span class="text-error-500">{{ $errors->first('agency') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.eligibilityTest.fields.agency_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="year">{{ trans('cruds.eligibilityTest.fields.year') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date {{ $errors->has('year') ? 'is-invalid' : '' }}" type="text" name="year" id="year" value="{{ old('year') }}" required>
                @if($errors->has('year'))
                    <span class="text-error-500">{{ $errors->first('year') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.eligibilityTest.fields.year_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="subject">{{ trans('cruds.eligibilityTest.fields.subject') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" name="subject" id="subject" value="{{ old('subject', '') }}" required>
                @if($errors->has('subject'))
                    <span class="text-error-500">{{ $errors->first('subject') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.eligibilityTest.fields.subject_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="user_id">{{ trans('cruds.eligibilityTest.fields.user') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-error-500">{{ $errors->first('user') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.eligibilityTest.fields.user_helper') }}</span>
            </div>
            <div class="mb-4">
                <button class="inline-flex rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-error-600" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection