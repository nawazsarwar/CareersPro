@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.post.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.posts.update", [$post->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="advertisement_id">{{ trans('cruds.post.fields.advertisement') }}</label>
                <select class="form-control select2 {{ $errors->has('advertisement') ? 'is-invalid' : '' }}" name="advertisement_id" id="advertisement_id" required>
                    @foreach($advertisements as $id => $entry)
                        <option value="{{ $id }}" {{ (old('advertisement_id') ? old('advertisement_id') : $post->advertisement->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('advertisement'))
                    <span class="text-danger">{{ $errors->first('advertisement') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.advertisement_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="posttype_id">{{ trans('cruds.post.fields.posttype') }}</label>
                <select class="form-control select2 {{ $errors->has('posttype') ? 'is-invalid' : '' }}" name="posttype_id" id="posttype_id" required>
                    @foreach($posttypes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('posttype_id') ? old('posttype_id') : $post->posttype->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('posttype'))
                    <span class="text-danger">{{ $errors->first('posttype') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.posttype_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="serial_no">{{ trans('cruds.post.fields.serial_no') }}</label>
                <input class="form-control {{ $errors->has('serial_no') ? 'is-invalid' : '' }}" type="number" name="serial_no" id="serial_no" value="{{ old('serial_no', $post->serial_no) }}" step="1">
                @if($errors->has('serial_no'))
                    <span class="text-danger">{{ $errors->first('serial_no') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.serial_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.post.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subject">{{ trans('cruds.post.fields.subject') }}</label>
                <input class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" name="subject" id="subject" value="{{ old('subject', $post->subject) }}">
                @if($errors->has('subject'))
                    <span class="text-danger">{{ $errors->first('subject') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.subject_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="slug">{{ trans('cruds.post.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" required>
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.post.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $post->description) !!}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="vacancies">{{ trans('cruds.post.fields.vacancies') }}</label>
                <input class="form-control {{ $errors->has('vacancies') ? 'is-invalid' : '' }}" type="number" name="vacancies" id="vacancies" value="{{ old('vacancies', $post->vacancies) }}" step="1" required>
                @if($errors->has('vacancies'))
                    <span class="text-danger">{{ $errors->first('vacancies') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.vacancies_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="location">{{ trans('cruds.post.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $post->location) }}" required>
                @if($errors->has('location'))
                    <span class="text-danger">{{ $errors->first('location') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pay_level">{{ trans('cruds.post.fields.pay_level') }}</label>
                <input class="form-control {{ $errors->has('pay_level') ? 'is-invalid' : '' }}" type="text" name="pay_level" id="pay_level" value="{{ old('pay_level', $post->pay_level) }}" required>
                @if($errors->has('pay_level'))
                    <span class="text-danger">{{ $errors->first('pay_level') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.pay_level_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pay_range">{{ trans('cruds.post.fields.pay_range') }}</label>
                <input class="form-control {{ $errors->has('pay_range') ? 'is-invalid' : '' }}" type="text" name="pay_range" id="pay_range" value="{{ old('pay_range', $post->pay_range) }}" required>
                @if($errors->has('pay_range'))
                    <span class="text-danger">{{ $errors->first('pay_range') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.pay_range_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fee">{{ trans('cruds.post.fields.fee') }}</label>
                <input class="form-control {{ $errors->has('fee') ? 'is-invalid' : '' }}" type="number" name="fee" id="fee" value="{{ old('fee', $post->fee) }}" step="0.01" required>
                @if($errors->has('fee'))
                    <span class="text-danger">{{ $errors->first('fee') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.fee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="opening_date">{{ trans('cruds.post.fields.opening_date') }}</label>
                <input class="form-control datetime {{ $errors->has('opening_date') ? 'is-invalid' : '' }}" type="text" name="opening_date" id="opening_date" value="{{ old('opening_date', $post->opening_date) }}">
                @if($errors->has('opening_date'))
                    <span class="text-danger">{{ $errors->first('opening_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.opening_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="closing_date">{{ trans('cruds.post.fields.closing_date') }}</label>
                <input class="form-control datetime {{ $errors->has('closing_date') ? 'is-invalid' : '' }}" type="text" name="closing_date" id="closing_date" value="{{ old('closing_date', $post->closing_date) }}">
                @if($errors->has('closing_date'))
                    <span class="text-danger">{{ $errors->first('closing_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.closing_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="payment_closing_date">{{ trans('cruds.post.fields.payment_closing_date') }}</label>
                <input class="form-control datetime {{ $errors->has('payment_closing_date') ? 'is-invalid' : '' }}" type="text" name="payment_closing_date" id="payment_closing_date" value="{{ old('payment_closing_date', $post->payment_closing_date) }}">
                @if($errors->has('payment_closing_date'))
                    <span class="text-danger">{{ $errors->first('payment_closing_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.payment_closing_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="withdrawn">{{ trans('cruds.post.fields.withdrawn') }}</label>
                <input class="form-control {{ $errors->has('withdrawn') ? 'is-invalid' : '' }}" type="number" name="withdrawn" id="withdrawn" value="{{ old('withdrawn', $post->withdrawn) }}" step="1" required>
                @if($errors->has('withdrawn'))
                    <span class="text-danger">{{ $errors->first('withdrawn') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.withdrawn_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="status">{{ trans('cruds.post.fields.status') }}</label>
                <input class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" type="number" name="status" id="status" value="{{ old('status', $post->status) }}" step="1" required>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.post.fields.remarks') }}</label>
                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks', $post->remarks) }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.remarks_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="added_by_id">{{ trans('cruds.post.fields.added_by') }}</label>
                <select class="form-control select2 {{ $errors->has('added_by') ? 'is-invalid' : '' }}" name="added_by_id" id="added_by_id" required>
                    @foreach($added_bies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('added_by_id') ? old('added_by_id') : $post->added_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('added_by'))
                    <span class="text-danger">{{ $errors->first('added_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.added_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="test_date">{{ trans('cruds.post.fields.test_date') }}</label>
                <input class="form-control date {{ $errors->has('test_date') ? 'is-invalid' : '' }}" type="text" name="test_date" id="test_date" value="{{ old('test_date', $post->test_date) }}">
                @if($errors->has('test_date'))
                    <span class="text-danger">{{ $errors->first('test_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.test_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="test_reporting_time">{{ trans('cruds.post.fields.test_reporting_time') }}</label>
                <input class="form-control {{ $errors->has('test_reporting_time') ? 'is-invalid' : '' }}" type="text" name="test_reporting_time" id="test_reporting_time" value="{{ old('test_reporting_time', $post->test_reporting_time) }}">
                @if($errors->has('test_reporting_time'))
                    <span class="text-danger">{{ $errors->first('test_reporting_time') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.test_reporting_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="gate_closing_time">{{ trans('cruds.post.fields.gate_closing_time') }}</label>
                <input class="form-control {{ $errors->has('gate_closing_time') ? 'is-invalid' : '' }}" type="text" name="gate_closing_time" id="gate_closing_time" value="{{ old('gate_closing_time', $post->gate_closing_time) }}">
                @if($errors->has('gate_closing_time'))
                    <span class="text-danger">{{ $errors->first('gate_closing_time') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.gate_closing_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="scheduled_test_start">{{ trans('cruds.post.fields.scheduled_test_start') }}</label>
                <input class="form-control {{ $errors->has('scheduled_test_start') ? 'is-invalid' : '' }}" type="text" name="scheduled_test_start" id="scheduled_test_start" value="{{ old('scheduled_test_start', $post->scheduled_test_start) }}">
                @if($errors->has('scheduled_test_start'))
                    <span class="text-danger">{{ $errors->first('scheduled_test_start') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.scheduled_test_start_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="test_duration">{{ trans('cruds.post.fields.test_duration') }}</label>
                <input class="form-control {{ $errors->has('test_duration') ? 'is-invalid' : '' }}" type="text" name="test_duration" id="test_duration" value="{{ old('test_duration', $post->test_duration) }}">
                @if($errors->has('test_duration'))
                    <span class="text-danger">{{ $errors->first('test_duration') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.test_duration_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="interview_date">{{ trans('cruds.post.fields.interview_date') }}</label>
                <input class="form-control date {{ $errors->has('interview_date') ? 'is-invalid' : '' }}" type="text" name="interview_date" id="interview_date" value="{{ old('interview_date', $post->interview_date) }}">
                @if($errors->has('interview_date'))
                    <span class="text-danger">{{ $errors->first('interview_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.interview_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="interview_time">{{ trans('cruds.post.fields.interview_time') }}</label>
                <input class="form-control {{ $errors->has('interview_time') ? 'is-invalid' : '' }}" type="text" name="interview_time" id="interview_time" value="{{ old('interview_time', $post->interview_time) }}">
                @if($errors->has('interview_time'))
                    <span class="text-danger">{{ $errors->first('interview_time') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.interview_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="interview_venue">{{ trans('cruds.post.fields.interview_venue') }}</label>
                <input class="form-control {{ $errors->has('interview_venue') ? 'is-invalid' : '' }}" type="text" name="interview_venue" id="interview_venue" value="{{ old('interview_venue', $post->interview_venue) }}">
                @if($errors->has('interview_venue'))
                    <span class="text-danger">{{ $errors->first('interview_venue') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.interview_venue_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.posts.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $post->id ?? 0 }}');
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

@endsection