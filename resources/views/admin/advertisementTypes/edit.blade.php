@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.edit') }} {{ trans('cruds.advertisementType.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.advertisement-types.update", [$advertisementType->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label for="title">{{ trans('cruds.advertisementType.fields.title') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $advertisementType->title) }}">
                @if($errors->has('title'))
                    <span class="text-error-500">{{ $errors->first('title') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.advertisementType.fields.title_helper') }}</span>
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