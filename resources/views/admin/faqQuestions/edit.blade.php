@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.edit') }} {{ trans('cruds.faqQuestion.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.faq-questions.update", [$faqQuestion->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label class="required" for="category_id">{{ trans('cruds.faqQuestion.fields.category') }}</label>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $faqQuestion->category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <span class="text-error-500">{{ $errors->first('category') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.faqQuestion.fields.category_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="question">{{ trans('cruds.faqQuestion.fields.question') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('question') ? 'is-invalid' : '' }}" name="question" id="question" required>{{ old('question', $faqQuestion->question) }}</textarea>
                @if($errors->has('question'))
                    <span class="text-error-500">{{ $errors->first('question') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.faqQuestion.fields.question_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="answer">{{ trans('cruds.faqQuestion.fields.answer') }}</label>
                <textarea class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('answer') ? 'is-invalid' : '' }}" name="answer" id="answer" required>{{ old('answer', $faqQuestion->answer) }}</textarea>
                @if($errors->has('answer'))
                    <span class="text-error-500">{{ $errors->first('answer') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.faqQuestion.fields.answer_helper') }}</span>
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