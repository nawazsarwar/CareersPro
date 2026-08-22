@extends('layouts.admin')
@section('content')

<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route("admin.users.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-error-500">{{ $errors->first('name') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <span class="text-error-500">{{ $errors->first('email') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                @if($errors->has('password'))
                    <span class="text-error-500">{{ $errors->first('password') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="mb-4">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="inline-flex rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="inline-flex rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <span class="text-error-500">{{ $errors->first('roles') }}</span>
                @endif
                <span class="mt-1 text-xs text-gray-500">{{ trans('cruds.user.fields.roles_helper') }}</span>
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