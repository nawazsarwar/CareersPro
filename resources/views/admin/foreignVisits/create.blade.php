@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.foreignVisit.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.foreign-visits.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="required" for="country_id">{{ trans('cruds.foreignVisit.fields.country') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country_id" id="country_id" required>
                    @foreach($countries as $id => $entry)
                        <option value="{{ $id }}" {{ old('country_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <span class="text-error-500">{{ $errors->first('country') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.foreignVisit.fields.country_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="date">{{ trans('cruds.foreignVisit.fields.date') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                @if($errors->has('date'))
                    <span class="text-error-500">{{ $errors->first('date') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.foreignVisit.fields.date_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="duration">{{ trans('cruds.foreignVisit.fields.duration') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('duration') ? 'is-invalid' : '' }}" type="text" name="duration" id="duration" value="{{ old('duration', '') }}" required>
                @if($errors->has('duration'))
                    <span class="text-error-500">{{ $errors->first('duration') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.foreignVisit.fields.duration_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="purpose">{{ trans('cruds.foreignVisit.fields.purpose') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('purpose') ? 'is-invalid' : '' }}" type="text" name="purpose" id="purpose" value="{{ old('purpose', '') }}" required>
                @if($errors->has('purpose'))
                    <span class="text-error-500">{{ $errors->first('purpose') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.foreignVisit.fields.purpose_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="user_id">{{ trans('cruds.foreignVisit.fields.user') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-error-500">{{ $errors->first('user') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.foreignVisit.fields.user_helper') }}</span>
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