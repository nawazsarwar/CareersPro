@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.postType.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.post-types.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="required" for="name">{{ trans('cruds.postType.fields.name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-error-500">{{ $errors->first('name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postType.fields.name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="pdf_template">{{ trans('cruds.postType.fields.pdf_template') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('pdf_template') ? 'is-invalid' : '' }}" type="text" name="pdf_template" id="pdf_template" value="{{ old('pdf_template', '') }}" required>
                @if($errors->has('pdf_template'))
                    <span class="text-error-500">{{ $errors->first('pdf_template') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postType.fields.pdf_template_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="submission_venue">{{ trans('cruds.postType.fields.submission_venue') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('submission_venue') ? 'is-invalid' : '' }}" type="text" name="submission_venue" id="submission_venue" value="{{ old('submission_venue', '') }}">
                @if($errors->has('submission_venue'))
                    <span class="text-error-500">{{ $errors->first('submission_venue') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postType.fields.submission_venue_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="status">{{ trans('cruds.postType.fields.status') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('status') ? 'is-invalid' : '' }}" type="text" name="status" id="status" value="{{ old('status', '') }}">
                @if($errors->has('status'))
                    <span class="text-error-500">{{ $errors->first('status') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postType.fields.status_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="remarks">{{ trans('cruds.postType.fields.remarks') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-error-500">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.postType.fields.remarks_helper') }}</span>
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