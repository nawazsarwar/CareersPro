@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.academicQualification.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.academic-qualifications.update", [$academicQualification->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name_id">{{ trans('cruds.academicQualification.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id" required>
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ (old('name_id') ? old('name_id') : $academicQualification->name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="course">{{ trans('cruds.academicQualification.fields.course') }}</label>
                <input class="form-control {{ $errors->has('course') ? 'is-invalid' : '' }}" type="text" name="course" id="course" value="{{ old('course', $academicQualification->course) }}" required>
                @if($errors->has('course'))
                    <span class="text-danger">{{ $errors->first('course') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.course_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="board_id">{{ trans('cruds.academicQualification.fields.board') }}</label>
                <select class="form-control select2 {{ $errors->has('board') ? 'is-invalid' : '' }}" name="board_id" id="board_id" required>
                    @foreach($boards as $id => $entry)
                        <option value="{{ $id }}" {{ (old('board_id') ? old('board_id') : $academicQualification->board->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('board'))
                    <span class="text-danger">{{ $errors->first('board') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.board_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="year">{{ trans('cruds.academicQualification.fields.year') }}</label>
                <input class="form-control date {{ $errors->has('year') ? 'is-invalid' : '' }}" type="text" name="year" id="year" value="{{ old('year', $academicQualification->year) }}" required>
                @if($errors->has('year'))
                    <span class="text-danger">{{ $errors->first('year') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.year_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.academicQualification.fields.division') }}</label>
                <select class="form-control {{ $errors->has('division') ? 'is-invalid' : '' }}" name="division" id="division" required>
                    <option value disabled {{ old('division', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\AcademicQualification::DIVISION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('division', $academicQualification->division) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('division'))
                    <span class="text-danger">{{ $errors->first('division') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.division_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="percentage">{{ trans('cruds.academicQualification.fields.percentage') }}</label>
                <input class="form-control {{ $errors->has('percentage') ? 'is-invalid' : '' }}" type="number" name="percentage" id="percentage" value="{{ old('percentage', $academicQualification->percentage) }}" step="0.01" max="100">
                @if($errors->has('percentage'))
                    <span class="text-danger">{{ $errors->first('percentage') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.percentage_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cgpa">{{ trans('cruds.academicQualification.fields.cgpa') }}</label>
                <input class="form-control {{ $errors->has('cgpa') ? 'is-invalid' : '' }}" type="number" name="cgpa" id="cgpa" value="{{ old('cgpa', $academicQualification->cgpa) }}" step="0.01">
                @if($errors->has('cgpa'))
                    <span class="text-danger">{{ $errors->first('cgpa') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.cgpa_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="subjects">{{ trans('cruds.academicQualification.fields.subjects') }}</label>
                <input class="form-control {{ $errors->has('subjects') ? 'is-invalid' : '' }}" type="text" name="subjects" id="subjects" value="{{ old('subjects', $academicQualification->subjects) }}" required>
                @if($errors->has('subjects'))
                    <span class="text-danger">{{ $errors->first('subjects') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.subjects_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.academicQualification.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $academicQualification->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.academicQualification.fields.remarks') }}</label>
                <input class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" type="text" name="remarks" id="remarks" value="{{ old('remarks', $academicQualification->remarks) }}">
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.remarks_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="document">{{ trans('cruds.academicQualification.fields.document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('document') ? 'is-invalid' : '' }}" id="document-dropzone">
                </div>
                @if($errors->has('document'))
                    <span class="text-danger">{{ $errors->first('document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.document_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.academicQualification.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $academicQualification->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicQualification.fields.user_helper') }}</span>
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
    url: '{{ route('admin.academic-qualifications.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
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
@if(isset($academicQualification) && $academicQualification->document)
      var file = {!! json_encode($academicQualification->document) !!}
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