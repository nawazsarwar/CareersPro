@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.postType.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.post-types.update", [$postType->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.postType.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $postType->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.postType.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="pdf_template">{{ trans('cruds.postType.fields.pdf_template') }}</label>
                            <input class="form-control" type="text" name="pdf_template" id="pdf_template" value="{{ old('pdf_template', $postType->pdf_template) }}" required>
                            @if($errors->has('pdf_template'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pdf_template') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.postType.fields.pdf_template_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="submission_venue">{{ trans('cruds.postType.fields.submission_venue') }}</label>
                            <input class="form-control" type="text" name="submission_venue" id="submission_venue" value="{{ old('submission_venue', $postType->submission_venue) }}">
                            @if($errors->has('submission_venue'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('submission_venue') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.postType.fields.submission_venue_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="status">{{ trans('cruds.postType.fields.status') }}</label>
                            <input class="form-control" type="text" name="status" id="status" value="{{ old('status', $postType->status) }}">
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.postType.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ trans('cruds.postType.fields.remarks') }}</label>
                            <textarea class="form-control" name="remarks" id="remarks">{{ old('remarks', $postType->remarks) }}</textarea>
                            @if($errors->has('remarks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remarks') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection