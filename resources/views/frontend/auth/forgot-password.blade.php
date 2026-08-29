@extends('layouts.auth')

@section('title', __('auth.forgot_password'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.forgot_password') }}</h2>
    <p class="mt-2 text-sm text-[var(--ink-muted)]">{{ __('auth.forgot_password_hint') }}</p>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('frontend.password.email') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.email') }}</label>
            <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>
        <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('auth.send_reset_link') }}</button>
    </form>
@endsection
