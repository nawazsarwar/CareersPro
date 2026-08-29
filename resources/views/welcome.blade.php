@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <div class="mx-auto flex min-h-screen max-w-xl flex-col justify-center px-6">
        <h1 class="font-[var(--font-display)] text-3xl">{{ config('app.name') }}</h1>
        <p class="mt-2 text-sm text-[var(--ink-muted)]">{{ __('global.foundation_placeholder') }}</p>
        <p class="mt-6 text-sm">
            <a class="underline" href="{{ route('frontend.login') }}">{{ __('auth.sign_in') }}</a>
        </p>
    </div>
@endsection
