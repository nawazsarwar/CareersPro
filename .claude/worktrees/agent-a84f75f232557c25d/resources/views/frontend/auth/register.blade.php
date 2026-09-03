@extends('layouts.auth')

@section('title', __('auth.create_account'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.create_account') }}</h2>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('frontend.register') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.full_name') }}</label>
            <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.email') }}</label>
            <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
            <p class="mt-1 text-xs text-[var(--ink-faint)]">{{ __('auth.password_policy') }}</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>

        <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="consent" value="1" class="mt-1" @checked(old('consent'))>
            <span>{{ __('auth.consent', ['version' => config('app.privacy_notice_version')]) }}</span>
        </label>

        <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('auth.create_account') }}</button>
    </form>

    <p class="mt-6 text-sm">
        <a class="underline" href="{{ route('frontend.login') }}">{{ __('auth.have_account') }}</a>
    </p>
@endsection
