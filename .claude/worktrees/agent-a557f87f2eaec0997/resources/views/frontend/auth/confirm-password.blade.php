@extends('layouts.auth')

@section('title', __('auth.confirm_password'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.confirm_password') }}</h2>
    <p class="mt-2 text-sm text-[var(--ink-muted)]">{{ __('auth.confirm_password_hint') }}</p>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('frontend.password.confirm') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.password') }}</label>
            <input id="password" name="password" type="password" required autofocus autocomplete="current-password"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>
        <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('auth.confirm') }}</button>
    </form>
@endsection
