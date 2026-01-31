@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.employmentHistory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.employment-histories.update", [$employmentHistory->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.employmentHistory.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $employmentHistory->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="employer">{{ trans('cruds.employmentHistory.fields.employer') }}</label>
                <input class="form-control {{ $errors->has('employer') ? 'is-invalid' : '' }}" type="text" name="employer" id="employer" value="{{ old('employer', $employmentHistory->employer) }}" required>
                @if($errors->has('employer'))
                    <span class="text-danger">{{ $errors->first('employer') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.employer_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.employmentHistory.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\EmploymentHistory::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $employmentHistory->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="designation">{{ trans('cruds.employmentHistory.fields.designation') }}</label>
                <input class="form-control {{ $errors->has('designation') ? 'is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation', $employmentHistory->designation) }}" required>
                @if($errors->has('designation'))
                    <span class="text-danger">{{ $errors->first('designation') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.designation_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="from">{{ trans('cruds.employmentHistory.fields.from') }}</label>
                <input class="form-control date {{ $errors->has('from') ? 'is-invalid' : '' }}" type="text" name="from" id="from" value="{{ old('from', $employmentHistory->from) }}" required>
                @if($errors->has('from'))
                    <span class="text-danger">{{ $errors->first('from') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to">{{ trans('cruds.employmentHistory.fields.to') }}</label>
                <input class="form-control date {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to', $employmentHistory->to) }}">
                @if($errors->has('to'))
                    <span class="text-danger">{{ $errors->first('to') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.to_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="responsibilities">{{ trans('cruds.employmentHistory.fields.responsibilities') }}</label>
                <input class="form-control {{ $errors->has('responsibilities') ? 'is-invalid' : '' }}" type="text" name="responsibilities" id="responsibilities" value="{{ old('responsibilities', $employmentHistory->responsibilities) }}" required>
                @if($errors->has('responsibilities'))
                    <span class="text-danger">{{ $errors->first('responsibilities') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.responsibilities_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="reason_for_leaving">{{ trans('cruds.employmentHistory.fields.reason_for_leaving') }}</label>
                <input class="form-control {{ $errors->has('reason_for_leaving') ? 'is-invalid' : '' }}" type="text" name="reason_for_leaving" id="reason_for_leaving" value="{{ old('reason_for_leaving', $employmentHistory->reason_for_leaving) }}" required>
                @if($errors->has('reason_for_leaving'))
                    <span class="text-danger">{{ $errors->first('reason_for_leaving') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.reason_for_leaving_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pay_band">{{ trans('cruds.employmentHistory.fields.pay_band') }}</label>
                <input class="form-control {{ $errors->has('pay_band') ? 'is-invalid' : '' }}" type="text" name="pay_band" id="pay_band" value="{{ old('pay_band', $employmentHistory->pay_band) }}" required>
                @if($errors->has('pay_band'))
                    <span class="text-danger">{{ $errors->first('pay_band') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.pay_band_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="basic_pay">{{ trans('cruds.employmentHistory.fields.basic_pay') }}</label>
                <input class="form-control {{ $errors->has('basic_pay') ? 'is-invalid' : '' }}" type="text" name="basic_pay" id="basic_pay" value="{{ old('basic_pay', $employmentHistory->basic_pay) }}" required>
                @if($errors->has('basic_pay'))
                    <span class="text-danger">{{ $errors->first('basic_pay') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.basic_pay_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="gross_pay">{{ trans('cruds.employmentHistory.fields.gross_pay') }}</label>
                <input class="form-control {{ $errors->has('gross_pay') ? 'is-invalid' : '' }}" type="text" name="gross_pay" id="gross_pay" value="{{ old('gross_pay', $employmentHistory->gross_pay) }}" required>
                @if($errors->has('gross_pay'))
                    <span class="text-danger">{{ $errors->first('gross_pay') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.employmentHistory.fields.gross_pay_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection