@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.academicQualification.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.academic-qualifications.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="required" for="name_id">{{ trans('cruds.academicQualification.fields.name') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id" required>
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-error-500">{{ $errors->first('name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="course">{{ trans('cruds.academicQualification.fields.course') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('course') ? 'is-invalid' : '' }}" type="text" name="course" id="course" value="{{ old('course', '') }}" required>
                @if($errors->has('course'))
                    <span class="text-error-500">{{ $errors->first('course') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.course_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="board_id">{{ trans('cruds.academicQualification.fields.board') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('board') ? 'is-invalid' : '' }}" name="board_id" id="board_id" required>
                    @foreach($boards as $id => $entry)
                        <option value="{{ $id }}" {{ old('board_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('board'))
                    <span class="text-error-500">{{ $errors->first('board') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.board_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="year">{{ trans('cruds.academicQualification.fields.year') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 date {{ $errors->has('year') ? 'is-invalid' : '' }}" type="text" name="year" id="year" value="{{ old('year') }}" required>
                @if($errors->has('year'))
                    <span class="text-error-500">{{ $errors->first('year') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.year_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required">{{ trans('cruds.academicQualification.fields.division') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('division') ? 'is-invalid' : '' }}" name="division" id="division" required>
                    <option value disabled {{ old('division', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\AcademicQualification::DIVISION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('division', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('division'))
                    <span class="text-error-500">{{ $errors->first('division') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.division_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="percentage">{{ trans('cruds.academicQualification.fields.percentage') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('percentage') ? 'is-invalid' : '' }}" type="number" name="percentage" id="percentage" value="{{ old('percentage', '') }}" step="0.01" max="100">
                @if($errors->has('percentage'))
                    <span class="text-error-500">{{ $errors->first('percentage') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.percentage_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="cgpa">{{ trans('cruds.academicQualification.fields.cgpa') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('cgpa') ? 'is-invalid' : '' }}" type="number" name="cgpa" id="cgpa" value="{{ old('cgpa', '') }}" step="0.01" required>
                @if($errors->has('cgpa'))
                    <span class="text-error-500">{{ $errors->first('cgpa') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.cgpa_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="subjects">{{ trans('cruds.academicQualification.fields.subjects') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('subjects') ? 'is-invalid' : '' }}" type="text" name="subjects" id="subjects" value="{{ old('subjects', '') }}" required>
                @if($errors->has('subjects'))
                    <span class="text-error-500">{{ $errors->first('subjects') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.subjects_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="title">{{ trans('cruds.academicQualification.fields.title') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-error-500">{{ $errors->first('title') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.title_helper') }}</span>
            </div>
            <div class="mb-4">
                <label for="remarks">{{ trans('cruds.academicQualification.fields.remarks') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('remarks') ? 'is-invalid' : '' }}" type="text" name="remarks" id="remarks" value="{{ old('remarks', '') }}">
                @if($errors->has('remarks'))
                    <span class="text-error-500">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.remarks_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="document">{{ trans('cruds.academicQualification.fields.document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('document') ? 'is-invalid' : '' }}" id="document-dropzone">
                </div>
                @if($errors->has('document'))
                    <span class="text-error-500">{{ $errors->first('document') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.document_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="user_id">{{ trans('cruds.academicQualification.fields.user') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-error-500">{{ $errors->first('user') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.academicQualification.fields.user_helper') }}</span>
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