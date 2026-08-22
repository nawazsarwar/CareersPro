@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.referee.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.referees.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="mb-4">
                            <label class="required" for="name">{{ trans('cruds.referee.fields.name') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.name_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="designation">{{ trans('cruds.referee.fields.designation') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="designation" id="designation" value="{{ old('designation', '') }}" required>
                            @if($errors->has('designation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('designation') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.designation_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="mobile">{{ trans('cruds.referee.fields.mobile') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="mobile" id="mobile" value="{{ old('mobile', '') }}" required>
                            @if($errors->has('mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.mobile_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="email">{{ trans('cruds.referee.fields.email') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="email" name="email" id="email" value="{{ old('email') }}" required>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.email_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="address">{{ trans('cruds.referee.fields.address') }}</label>
                            <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" name="address" id="address" required>{{ old('address') }}</textarea>
                            @if($errors->has('address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.referee.fields.address_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="period_known">{{ trans('cruds.referee.fields.period_known') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="period_known" id="period_known" value="{{ old('period_known', '') }}" required>
                            @if($errors->has('period_known'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('period_known') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection