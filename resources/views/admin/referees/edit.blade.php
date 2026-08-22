@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.edit') }} {{ trans('cruds.referee.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.referees.update", [$referee->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label class="required" for="name">{{ trans('cruds.referee.fields.name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $referee->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-error-500">{{ $errors->first('name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="designation">{{ trans('cruds.referee.fields.designation') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('designation') ? 'is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation', $referee->designation) }}" required>
                @if($errors->has('designation'))
                    <span class="text-error-500">{{ $errors->first('designation') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.designation_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="mobile">{{ trans('cruds.referee.fields.mobile') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="text" name="mobile" id="mobile" value="{{ old('mobile', $referee->mobile) }}" required>
                @if($errors->has('mobile'))
                    <span class="text-error-500">{{ $errors->first('mobile') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.mobile_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="email">{{ trans('cruds.referee.fields.email') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $referee->email) }}" required>
                @if($errors->has('email'))
                    <span class="text-error-500">{{ $errors->first('email') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.email_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="address">{{ trans('cruds.referee.fields.address') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address" required>{{ old('address', $referee->address) }}</textarea>
                @if($errors->has('address'))
                    <span class="text-error-500">{{ $errors->first('address') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.address_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="period_known">{{ trans('cruds.referee.fields.period_known') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('period_known') ? 'is-invalid' : '' }}" type="text" name="period_known" id="period_known" value="{{ old('period_known', $referee->period_known) }}" required>
                @if($errors->has('period_known'))
                    <span class="text-error-500">{{ $errors->first('period_known') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.period_known_helper') }}</span>
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