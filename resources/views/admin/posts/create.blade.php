@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.post.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.posts.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="required" for="advertisement_id">{{ trans('cruds.post.fields.advertisement') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('advertisement') ? 'is-invalid' : '' }}" name="advertisement_id" id="advertisement_id" required>
                    @foreach($advertisements as $id => $entry)
                        <option value="{{ $id }}" {{ old('advertisement_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('advertisement'))
                    <span class="text-error-500">{{ $errors->first('advertisement') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.advertisement_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="posttype_id">{{ trans('cruds.post.fields.posttype') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('posttype') ? 'is-invalid' : '' }}" name="posttype_id" id="posttype_id" required>
                    @foreach($posttypes as $id => $entry)
                        <option value="{{ $id }}" {{ old('posttype_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('posttype'))
                    <span class="text-error-500">{{ $errors->first('posttype') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.posttype_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="serial_no">{{ trans('cruds.post.fields.serial_no') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('serial_no') ? 'is-invalid' : '' }}" type="number" name="serial_no" id="serial_no" value="{{ old('serial_no', '') }}" step="1">
                @if($errors->has('serial_no'))
                    <span class="text-error-500">{{ $errors->first('serial_no') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.serial_no_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="title">{{ trans('cruds.post.fields.title') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-error-500">{{ $errors->first('title') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.title_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="slug">{{ trans('cruds.post.fields.slug') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                @if($errors->has('slug'))
                    <span class="text-error-500">{{ $errors->first('slug') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.slug_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="description">{{ trans('cruds.post.fields.description') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <span class="text-error-500">{{ $errors->first('description') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.description_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="vacancies">{{ trans('cruds.post.fields.vacancies') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('vacancies') ? 'is-invalid' : '' }}" type="number" name="vacancies" id="vacancies" value="{{ old('vacancies', '') }}" step="1" required>
                @if($errors->has('vacancies'))
                    <span class="text-error-500">{{ $errors->first('vacancies') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.vacancies_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="location">{{ trans('cruds.post.fields.location') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', '') }}" required>
                @if($errors->has('location'))
                    <span class="text-error-500">{{ $errors->first('location') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.location_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="pay_level">{{ trans('cruds.post.fields.pay_level') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('pay_level') ? 'is-invalid' : '' }}" type="text" name="pay_level" id="pay_level" value="{{ old('pay_level', '') }}" required>
                @if($errors->has('pay_level'))
                    <span class="text-error-500">{{ $errors->first('pay_level') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.pay_level_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="pay_range">{{ trans('cruds.post.fields.pay_range') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('pay_range') ? 'is-invalid' : '' }}" type="text" name="pay_range" id="pay_range" value="{{ old('pay_range', '') }}" required>
                @if($errors->has('pay_range'))
                    <span class="text-error-500">{{ $errors->first('pay_range') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.pay_range_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="fee">{{ trans('cruds.post.fields.fee') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('fee') ? 'is-invalid' : '' }}" type="number" name="fee" id="fee" value="{{ old('fee', '') }}" step="0.01" required>
                @if($errors->has('fee'))
                    <span class="text-error-500">{{ $errors->first('fee') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.fee_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="open_date">{{ trans('cruds.post.fields.open_date') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 datetime {{ $errors->has('open_date') ? 'is-invalid' : '' }}" type="text" name="open_date" id="open_date" value="{{ old('open_date') }}">
                @if($errors->has('open_date'))
                    <span class="text-error-500">{{ $errors->first('open_date') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.open_date_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="last_date">{{ trans('cruds.post.fields.last_date') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 datetime {{ $errors->has('last_date') ? 'is-invalid' : '' }}" type="text" name="last_date" id="last_date" value="{{ old('last_date') }}">
                @if($errors->has('last_date'))
                    <span class="text-error-500">{{ $errors->first('last_date') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.last_date_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="payment_end_date">{{ trans('cruds.post.fields.payment_end_date') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 datetime {{ $errors->has('payment_end_date') ? 'is-invalid' : '' }}" type="text" name="payment_end_date" id="payment_end_date" value="{{ old('payment_end_date') }}">
                @if($errors->has('payment_end_date'))
                    <span class="text-error-500">{{ $errors->first('payment_end_date') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.payment_end_date_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="withdrawn">{{ trans('cruds.post.fields.withdrawn') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('withdrawn') ? 'is-invalid' : '' }}" type="number" name="withdrawn" id="withdrawn" value="{{ old('withdrawn', '0') }}" step="1" required>
                @if($errors->has('withdrawn'))
                    <span class="text-error-500">{{ $errors->first('withdrawn') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.withdrawn_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="status">{{ trans('cruds.post.fields.status') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('status') ? 'is-invalid' : '' }}" type="number" name="status" id="status" value="{{ old('status', '1') }}" step="1" required>
                @if($errors->has('status'))
                    <span class="text-error-500">{{ $errors->first('status') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.status_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="remarks">{{ trans('cruds.post.fields.remarks') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-error-500">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.remarks_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="added_by_id">{{ trans('cruds.post.fields.added_by') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('added_by') ? 'is-invalid' : '' }}" name="added_by_id" id="added_by_id" required>
                    @foreach($added_bies as $id => $entry)
                        <option value="{{ $id }}" {{ old('added_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('added_by'))
                    <span class="text-error-500">{{ $errors->first('added_by') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.post.fields.added_by_helper') }}</span>
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