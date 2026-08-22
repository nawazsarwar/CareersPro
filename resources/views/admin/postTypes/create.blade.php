@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.postType.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.post-types.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.postType.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postType.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pdf_template">{{ trans('cruds.postType.fields.pdf_template') }}</label>
                <input class="form-control {{ $errors->has('pdf_template') ? 'is-invalid' : '' }}" type="text" name="pdf_template" id="pdf_template" value="{{ old('pdf_template', '') }}" required>
                @if($errors->has('pdf_template'))
                    <span class="text-danger">{{ $errors->first('pdf_template') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postType.fields.pdf_template_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="admit_card_template">{{ trans('cruds.postType.fields.admit_card_template') }}</label>
                <input class="form-control {{ $errors->has('admit_card_template') ? 'is-invalid' : '' }}" type="text" name="admit_card_template" id="admit_card_template" value="{{ old('admit_card_template', '') }}">
                @if($errors->has('admit_card_template'))
                    <span class="text-danger">{{ $errors->first('admit_card_template') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postType.fields.admit_card_template_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="interview_letter_template">{{ trans('cruds.postType.fields.interview_letter_template') }}</label>
                <input class="form-control {{ $errors->has('interview_letter_template') ? 'is-invalid' : '' }}" type="text" name="interview_letter_template" id="interview_letter_template" value="{{ old('interview_letter_template', '') }}">
                @if($errors->has('interview_letter_template'))
                    <span class="text-danger">{{ $errors->first('interview_letter_template') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postType.fields.interview_letter_template_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="submission_venue">{{ trans('cruds.postType.fields.submission_venue') }}</label>
                <input class="form-control {{ $errors->has('submission_venue') ? 'is-invalid' : '' }}" type="text" name="submission_venue" id="submission_venue" value="{{ old('submission_venue', '') }}">
                @if($errors->has('submission_venue'))
                    <span class="text-danger">{{ $errors->first('submission_venue') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postType.fields.submission_venue_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="status">{{ trans('cruds.postType.fields.status') }}</label>
                <input class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" type="text" name="status" id="status" value="{{ old('status', '') }}">
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postType.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.postType.fields.remarks') }}</label>
                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.postType.fields.remarks_helper') }}</span>
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