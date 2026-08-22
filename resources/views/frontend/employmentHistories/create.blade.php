@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.employmentHistory.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.employment-histories.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="mb-4">
                            <label class="required" for="employer">{{ trans('cruds.employmentHistory.fields.employer') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="employer" id="employer" value="{{ old('employer', '') }}" required>
                            @if($errors->has('employer'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('employer') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.employer_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label>{{ trans('cruds.employmentHistory.fields.type') }}</label>
                            <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" name="type" id="type">
                                <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\EmploymentHistory::TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.type_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="designation">{{ trans('cruds.employmentHistory.fields.designation') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="designation" id="designation" value="{{ old('designation', '') }}" required>
                            @if($errors->has('designation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('designation') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.designation_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="from">{{ trans('cruds.employmentHistory.fields.from') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date" type="text" name="from" id="from" value="{{ old('from') }}" required>
                            @if($errors->has('from'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.from_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label for="to">{{ trans('cruds.employmentHistory.fields.to') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date" type="text" name="to" id="to" value="{{ old('to') }}">
                            @if($errors->has('to'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.to_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="responsibilities">{{ trans('cruds.employmentHistory.fields.responsibilities') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="responsibilities" id="responsibilities" value="{{ old('responsibilities', '') }}" required>
                            @if($errors->has('responsibilities'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('responsibilities') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.responsibilities_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="reason_for_leaving">{{ trans('cruds.employmentHistory.fields.reason_for_leaving') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="reason_for_leaving" id="reason_for_leaving" value="{{ old('reason_for_leaving', '') }}" required>
                            @if($errors->has('reason_for_leaving'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reason_for_leaving') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.reason_for_leaving_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="pay_band">{{ trans('cruds.employmentHistory.fields.pay_band') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="pay_band" id="pay_band" value="{{ old('pay_band', '') }}" required>
                            @if($errors->has('pay_band'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pay_band') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.pay_band_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="basic_pay">{{ trans('cruds.employmentHistory.fields.basic_pay') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="basic_pay" id="basic_pay" value="{{ old('basic_pay', '') }}" required>
                            @if($errors->has('basic_pay'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('basic_pay') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.basic_pay_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="gross_pay">{{ trans('cruds.employmentHistory.fields.gross_pay') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="gross_pay" id="gross_pay" value="{{ old('gross_pay', '') }}" required>
                            @if($errors->has('gross_pay'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gross_pay') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.gross_pay_helper') }}</span>
                        </div>
                        <div class="mb-4">
                            <label class="required" for="user_id">{{ trans('cruds.employmentHistory.fields.user') }}</label>
                            <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.employmentHistory.fields.user_helper') }}</span>
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