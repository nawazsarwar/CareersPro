@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.edit') }} {{ trans('cruds.workExperience.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.work-experiences.update", [$workExperience->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label class="required" for="employer">{{ trans('cruds.workExperience.fields.employer') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('employer') ? 'is-invalid' : '' }}" type="text" name="employer" id="employer" value="{{ old('employer', $workExperience->employer) }}" required>
                @if($errors->has('employer'))
                    <span class="text-error-500">{{ $errors->first('employer') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.employer_helper') }}</span>
            </div>
            <div class="mb-4">
                <label>{{ trans('cruds.workExperience.fields.type') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\WorkExperience::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $workExperience->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <span class="text-error-500">{{ $errors->first('type') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.type_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="designation">{{ trans('cruds.workExperience.fields.designation') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('designation') ? 'is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation', $workExperience->designation) }}" required>
                @if($errors->has('designation'))
                    <span class="text-error-500">{{ $errors->first('designation') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.designation_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="from">{{ trans('cruds.workExperience.fields.from') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date {{ $errors->has('from') ? 'is-invalid' : '' }}" type="text" name="from" id="from" value="{{ old('from', $workExperience->from) }}" required>
                @if($errors->has('from'))
                    <span class="text-error-500">{{ $errors->first('from') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.from_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="to">{{ trans('cruds.workExperience.fields.to') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to', $workExperience->to) }}">
                @if($errors->has('to'))
                    <span class="text-error-500">{{ $errors->first('to') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.to_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="responsibilities">{{ trans('cruds.workExperience.fields.responsibilities') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('responsibilities') ? 'is-invalid' : '' }}" type="text" name="responsibilities" id="responsibilities" value="{{ old('responsibilities', $workExperience->responsibilities) }}" required>
                @if($errors->has('responsibilities'))
                    <span class="text-error-500">{{ $errors->first('responsibilities') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.responsibilities_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="reason_for_leaving">{{ trans('cruds.workExperience.fields.reason_for_leaving') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('reason_for_leaving') ? 'is-invalid' : '' }}" type="text" name="reason_for_leaving" id="reason_for_leaving" value="{{ old('reason_for_leaving', $workExperience->reason_for_leaving) }}" required>
                @if($errors->has('reason_for_leaving'))
                    <span class="text-error-500">{{ $errors->first('reason_for_leaving') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.reason_for_leaving_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="pay_band">{{ trans('cruds.workExperience.fields.pay_band') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('pay_band') ? 'is-invalid' : '' }}" type="text" name="pay_band" id="pay_band" value="{{ old('pay_band', $workExperience->pay_band) }}" required>
                @if($errors->has('pay_band'))
                    <span class="text-error-500">{{ $errors->first('pay_band') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.pay_band_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="basic_pay">{{ trans('cruds.workExperience.fields.basic_pay') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('basic_pay') ? 'is-invalid' : '' }}" type="text" name="basic_pay" id="basic_pay" value="{{ old('basic_pay', $workExperience->basic_pay) }}" required>
                @if($errors->has('basic_pay'))
                    <span class="text-error-500">{{ $errors->first('basic_pay') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.basic_pay_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="gross_pay">{{ trans('cruds.workExperience.fields.gross_pay') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('gross_pay') ? 'is-invalid' : '' }}" type="text" name="gross_pay" id="gross_pay" value="{{ old('gross_pay', $workExperience->gross_pay) }}" required>
                @if($errors->has('gross_pay'))
                    <span class="text-error-500">{{ $errors->first('gross_pay') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.gross_pay_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="user_id">{{ trans('cruds.workExperience.fields.user') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $workExperience->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-error-500">{{ $errors->first('user') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.workExperience.fields.user_helper') }}</span>
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