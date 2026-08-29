@extends('layouts.auth')

@section('title', __('auth.reset_password'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.reset_password') }}</h2>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('frontend.password.store') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.email') }}</label>
            <input id="email" name="email" type="email" required value="{{ old('email', $email) }}"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.new_password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
            <p class="mt-1 text-xs text-[var(--ink-faint)]">{{ __('auth.password_policy') }}</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>

        <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('auth.reset_password') }}</button>
    </form>
@endsection
