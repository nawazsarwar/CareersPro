@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.advertisement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.advertisements.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.advertisement.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.advertisement.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.advertisement.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dated">{{ trans('cruds.advertisement.fields.dated') }}</label>
                <input class="form-control date {{ $errors->has('dated') ? 'is-invalid' : '' }}" type="text" name="dated" id="dated" value="{{ old('dated') }}">
                @if($errors->has('dated'))
                    <span class="text-danger">{{ $errors->first('dated') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.dated_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="type_id">{{ trans('cruds.advertisement.fields.type') }}</label>
                <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type_id" id="type_id" required>
                    @foreach($types as $id => $entry)
                        <option value="{{ $id }}" {{ old('type_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="advertisement_url">{{ trans('cruds.advertisement.fields.advertisement_url') }}</label>
                <input class="form-control {{ $errors->has('advertisement_url') ? 'is-invalid' : '' }}" type="text" name="advertisement_url" id="advertisement_url" value="{{ old('advertisement_url', '') }}">
                @if($errors->has('advertisement_url'))
                    <span class="text-danger">{{ $errors->first('advertisement_url') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.advertisement_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="document">{{ trans('cruds.advertisement.fields.document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('document') ? 'is-invalid' : '' }}" id="document-dropzone">
                </div>
                @if($errors->has('document'))
                    <span class="text-danger">{{ $errors->first('document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="default_fee">{{ trans('cruds.advertisement.fields.default_fee') }}</label>
                <input class="form-control {{ $errors->has('default_fee') ? 'is-invalid' : '' }}" type="number" name="default_fee" id="default_fee" value="{{ old('default_fee', '') }}" step="0.01">
                @if($errors->has('default_fee'))
                    <span class="text-danger">{{ $errors->first('default_fee') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.default_fee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="default_opening_date">{{ trans('cruds.advertisement.fields.default_opening_date') }}</label>
                <input class="form-control datetime {{ $errors->has('default_opening_date') ? 'is-invalid' : '' }}" type="text" name="default_opening_date" id="default_opening_date" value="{{ old('default_opening_date') }}">
                @if($errors->has('default_opening_date'))
                    <span class="text-danger">{{ $errors->first('default_opening_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.default_opening_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="default_closing_date">{{ trans('cruds.advertisement.fields.default_closing_date') }}</label>
                <input class="form-control datetime {{ $errors->has('default_closing_date') ? 'is-invalid' : '' }}" type="text" name="default_closing_date" id="default_closing_date" value="{{ old('default_closing_date') }}">
                @if($errors->has('default_closing_date'))
                    <span class="text-danger">{{ $errors->first('default_closing_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.default_closing_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="default_payment_closing_date">{{ trans('cruds.advertisement.fields.default_payment_closing_date') }}</label>
                <input class="form-control datetime {{ $errors->has('default_payment_closing_date') ? 'is-invalid' : '' }}" type="text" name="default_payment_closing_date" id="default_payment_closing_date" value="{{ old('default_payment_closing_date') }}">
                @if($errors->has('default_payment_closing_date'))
                    <span class="text-danger">{{ $errors->first('default_payment_closing_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.default_payment_closing_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="status">{{ trans('cruds.advertisement.fields.status') }}</label>
                <input class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" type="text" name="status" id="status" value="{{ old('status', '') }}" required>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.advertisement.fields.remarks') }}</label>
                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.remarks_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="added_by_id">{{ trans('cruds.advertisement.fields.added_by') }}</label>
                <select class="form-control select2 {{ $errors->has('added_by') ? 'is-invalid' : '' }}" name="added_by_id" id="added_by_id">
                    @foreach($added_bies as $id => $entry)
                        <option value="{{ $id }}" {{ old('added_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('added_by'))
                    <span class="text-danger">{{ $errors->first('added_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.added_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approved_by_id">{{ trans('cruds.advertisement.fields.approved_by') }}</label>
                <select class="form-control select2 {{ $errors->has('approved_by') ? 'is-invalid' : '' }}" name="approved_by_id" id="approved_by_id">
                    @foreach($approved_bies as $id => $entry)
                        <option value="{{ $id }}" {{ old('approved_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('approved_by'))
                    <span class="text-danger">{{ $errors->first('approved_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.approved_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approved_at">{{ trans('cruds.advertisement.fields.approved_at') }}</label>
                <input class="form-control datetime {{ $errors->has('approved_at') ? 'is-invalid' : '' }}" type="text" name="approved_at" id="approved_at" value="{{ old('approved_at') }}">
                @if($errors->has('approved_at'))
                    <span class="text-danger">{{ $errors->first('approved_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisement.fields.approved_at_helper') }}</span>
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

@section('scripts')
<script>
    Dropzone.options.documentDropzone = {
    url: '{{ route('admin.advertisements.storeMedia') }}',
    maxFilesize: 50, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 50
    },
    success: function (file, response) {
      $('form').find('input[name="document"]').remove()
      $('form').append('<input type="hidden" name="document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($advertisement) && $advertisement->document)
      var file = {!! json_encode($advertisement->document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="document" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection