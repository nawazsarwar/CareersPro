@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.advertisement.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.advertisements.update", [$advertisement->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="title">{{ trans('cruds.advertisement.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $advertisement->title) }}" required>
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="slug">{{ trans('cruds.advertisement.fields.slug') }}</label>
                            <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug', $advertisement->slug) }}">
                            @if($errors->has('slug'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('slug') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.slug_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.advertisement.fields.description') }}</label>
                            <textarea class="form-control ckeditor" name="description" id="description">{!! old('description', $advertisement->description) !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="dated">{{ trans('cruds.advertisement.fields.dated') }}</label>
                            <input class="form-control date" type="text" name="dated" id="dated" value="{{ old('dated', $advertisement->dated) }}">
                            @if($errors->has('dated'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dated') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.dated_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="type_id">{{ trans('cruds.advertisement.fields.type') }}</label>
                            <select class="form-control select2" name="type_id" id="type_id" required>
                                @foreach($types as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('type_id') ? old('type_id') : $advertisement->type->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="advertisement_url">{{ trans('cruds.advertisement.fields.advertisement_url') }}</label>
                            <input class="form-control" type="text" name="advertisement_url" id="advertisement_url" value="{{ old('advertisement_url', $advertisement->advertisement_url) }}">
                            @if($errors->has('advertisement_url'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('advertisement_url') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.advertisement_url_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="document">{{ trans('cruds.advertisement.fields.document') }}</label>
                            <div class="needsclick dropzone" id="document-dropzone">
                            </div>
                            @if($errors->has('document'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('document') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.document_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="default_fee">{{ trans('cruds.advertisement.fields.default_fee') }}</label>
                            <input class="form-control" type="number" name="default_fee" id="default_fee" value="{{ old('default_fee', $advertisement->default_fee) }}" step="0.01">
                            @if($errors->has('default_fee'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('default_fee') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.default_fee_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="default_opening_date">{{ trans('cruds.advertisement.fields.default_opening_date') }}</label>
                            <input class="form-control datetime" type="text" name="default_opening_date" id="default_opening_date" value="{{ old('default_opening_date', $advertisement->default_opening_date) }}">
                            @if($errors->has('default_opening_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('default_opening_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.default_opening_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="default_closing_date">{{ trans('cruds.advertisement.fields.default_closing_date') }}</label>
                            <input class="form-control datetime" type="text" name="default_closing_date" id="default_closing_date" value="{{ old('default_closing_date', $advertisement->default_closing_date) }}">
                            @if($errors->has('default_closing_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('default_closing_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.default_closing_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="default_payment_closing_date">{{ trans('cruds.advertisement.fields.default_payment_closing_date') }}</label>
                            <input class="form-control datetime" type="text" name="default_payment_closing_date" id="default_payment_closing_date" value="{{ old('default_payment_closing_date', $advertisement->default_payment_closing_date) }}">
                            @if($errors->has('default_payment_closing_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('default_payment_closing_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.default_payment_closing_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="status">{{ trans('cruds.advertisement.fields.status') }}</label>
                            <input class="form-control" type="text" name="status" id="status" value="{{ old('status', $advertisement->status) }}" required>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ trans('cruds.advertisement.fields.remarks') }}</label>
                            <textarea class="form-control" name="remarks" id="remarks">{{ old('remarks', $advertisement->remarks) }}</textarea>
                            @if($errors->has('remarks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remarks') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.remarks_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="added_by_id">{{ trans('cruds.advertisement.fields.added_by') }}</label>
                            <select class="form-control select2" name="added_by_id" id="added_by_id">
                                @foreach($added_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('added_by_id') ? old('added_by_id') : $advertisement->added_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('added_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('added_by') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.added_by_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="approved_by_id">{{ trans('cruds.advertisement.fields.approved_by') }}</label>
                            <select class="form-control select2" name="approved_by_id" id="approved_by_id">
                                @foreach($approved_bies as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('approved_by_id') ? old('approved_by_id') : $advertisement->approved_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('approved_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approved_by') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.advertisement.fields.approved_by_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="approved_at">{{ trans('cruds.advertisement.fields.approved_at') }}</label>
                            <input class="form-control datetime" type="text" name="approved_at" id="approved_at" value="{{ old('approved_at', $advertisement->approved_at) }}">
                            @if($errors->has('approved_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approved_at') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('frontend.advertisements.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $advertisement->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    Dropzone.options.documentDropzone = {
    url: '{{ route('frontend.advertisements.storeMedia') }}',
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