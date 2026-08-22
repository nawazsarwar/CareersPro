@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.faqCategory.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.faq-categories.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="mb-4">
                            <label class="required" for="category">{{ trans('cruds.faqCategory.fields.category') }}</label>
                            <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" type="text" name="category" id="category" value="{{ old('category', '') }}" required>
                            @if($errors->has('category'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('category') }}
                                </div>
                            @endif
                            <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.faqCategory.fields.category_helper') }}</span>
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